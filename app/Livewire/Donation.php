<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Donor;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class Donation extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('email')
                            ->hint('(optional)'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->hint('(optional)')
                            ->autosize()
                            ->rows(3)
                            ->columnSpanFull(),
                        ToggleButtons::make('quick_amount')
                            ->label('Pilih Nominal Cepat')
                            ->options([
                                2000 => 'Rp 2.000',
                                5000 => 'Rp 5.000',
                                10000 => 'Rp 10.000',
                                25000 => 'Rp 25.000',
                                50000 => 'Rp 50.000',
                                100000 => 'Rp 100.000',
                            ])
                            ->inline()
                            ->columnSpanFull()
                            ->live()
                            ->afterStateUpdated(fn($state, Set $set) => $set('amount', $state)),

                        TextInput::make('amount')
                            ->label('Atau Masukkan Nominal Lain')
                            ->required()
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(2000)
                            ->live()
                            ->afterStateUpdated(fn($state, Set $set, Get $get) => $get('quick_amount') !== null && $state != $get('quick_amount') ? $set('quick_amount', null) : false)
                            ->currencyMask('.', ',', 2)
                            ->hint('Minimum Rp 2.000')
                            ->columnSpanFull(),
                        Select::make('type')
                            ->options([
                                Donor::INDIVIDUAL => 'Per Orangan',
                                Donor::ORGANIZATION => 'Organisasi',
                            ])
                            ->native(false)
                            ->required(),
                        Toggle::make('is_anonymous')
                            ->label('Sembunyikan Nama')
                            ->inline(false),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        // Validate form data
        $data = $this->form->getState();

        try {
            DB::beginTransaction();

            // Step 1: Find or create donor (prevent duplicates)
            $donor = Donor::findOrCreateByEmail(
                $data['email'] ?? null,
                [
                    'name' => $data['name'],
                    'email' => $data['email'] ?? null,
                    'phone' => null, // Could add phone field to form if needed
                    'address' => null,
                    'notes' => $data['notes'] ?? null,
                    'type' => $data['type'],
                    'is_anonymous' => $data['is_anonymous'] ?? false,
                ]
            );

            // Step 2: Get default donation category (you can change this logic)
            $category = Category::firstOrCreate(
                ['name' => 'Donasi Online', 'type' => Category::INCOME],
                [
                    'desc' => 'Donasi melalui payment gateway',
                    'is_active' => true,
                ]
            );

            // Step 3: Generate unique external ID for this transaction
            $externalId = 'DONATION-' . strtoupper(Str::random(10)) . '-' . time();

            // Step 4: Create transaction record with pending status
            $transaction = Transaction::create([
                'donor_id' => $donor->id,
                'user_id' => null, // Public donation, no user
                'category_id' => $category->id,
                'amount' => $data['amount'],
                'desc' => 'Donasi Online' . ($data['notes'] ? ' - ' . $data['notes'] : ''),
                'transaction_date' => now(),
                'payment_method' => Transaction::PAYMENT_GATEWAY_METHOD,
                'status' => Transaction::PENDING_STATUS,
                'reference_number' => $externalId,
                'external_id' => $externalId,
            ]);

            // Step 5: Create Xendit invoice
            Configuration::setXenditKey(config('services.xendit.secret_key'));
            $apiInstance = new InvoiceApi;

            $invoiceData = new CreateInvoiceRequest([
                'external_id' => $externalId,
                'amount' => (float) $data['amount'],
                'description' => 'Donasi untuk Mushola - ' . $donor->name,
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => $donor->name,
                    'email' => $donor->email ?? 'noreply@mushola.com',
                ],
                'currency' => 'IDR',
                'success_redirect_url' => route('donation.success'),
                'failure_redirect_url' => route('donation.failed'),
            ]);

            $invoice = $apiInstance->createInvoice($invoiceData);

            // Step 6: Create payment gateway record
            PaymentGateway::create([
                'transaction_id' => $transaction->id,
                'gateway_name' => PaymentGateway::XENDIT,
                'external_id' => $invoice['id'],
                'payment_channel' => null, // Will be updated by webhook when user selects payment method
                'amount' => $data['amount'],
                'currency' => 'IDR',
                'status' => PaymentGateway::PENDING,
                'payment_url' => $invoice['invoice_url'],
                'invoice_url' => $invoice['invoice_url'],
                'expired_at' => $invoice['expiry_date'] ? \Carbon\Carbon::parse($invoice['expiry_date']) : now()->addDay(),
                'callback_token' => $invoice['id'], // Use invoice ID as verification token
                'metadata' => [
                    'donor_name' => $donor->name,
                    'is_anonymous' => $donor->is_anonymous,
                ],
            ]);

            // Step 7: Update transaction with payment URL
            $transaction->update([
                'payment_url' => $invoice['invoice_url'],
                'expired_at' => $invoice['expiry_date'] ? \Carbon\Carbon::parse($invoice['expiry_date']) : now()->addDay(),
            ]);

            DB::commit();

            // Redirect to Xendit payment page
            $this->redirect(url: $invoice['invoice_url']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Show error notification
            Notification::make()
                ->title('Gagal Membuat Donasi')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    #[Title('Donation Page')]
    public function render()
    {
        return view('livewire.donation');
    }
}
