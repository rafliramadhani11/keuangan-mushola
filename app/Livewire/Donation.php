<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Donor;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                        Select::make('category_id')
                            ->label('Category')
                            ->options(
                                Category::query()
                                    ->where('type', Category::INCOME)
                                    ->pluck('name', 'id')
                            )
                            ->required()
                            ->native(false)
                            ->columnStart(1),
                        Select::make('type')
                            ->options([
                                Donor::INDIVIDUAL => 'Per Orangan',
                                Donor::ORGANIZATION => 'Organisasi',
                            ])
                            ->native(false)
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->columnStart(1),
                        TextInput::make('phone')
                            ->hint('ex: 898 1332 4231')
                            ->prefix('+62')
                            ->mask('999 9999 9999 9999')
                            ->required(),
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
                            ->afterStateUpdated(fn ($state, Set $set) => $set('amount', $state)),

                        TextInput::make('amount')
                            ->label('Atau Masukkan Nominal Lain')
                            ->required()
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(2000)
                            ->live()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => $get('quick_amount') !== null && $state != $get('quick_amount') ? $set('quick_amount', null) : false)
                            ->currencyMask('.', ',', 2)
                            ->hint('Minimum Rp 2.000')
                            ->columnSpanFull(),
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

            // Step 1: Create or update donor based on email
            if (! empty($data['phone'])) {
                // If email provided, find existing donor or create new one
                $donor = Donor::updateOrCreate(
                    ['phone' => $data['phone']], // Find by phone
                    [
                        'name' => $data['name'],
                        'type' => $data['type'],
                    ]
                );
            } else {
                // If no email, always create new donor
                $donor = Donor::create([
                    'name' => $data['name'],
                    'phone' => null,
                    'type' => $data['type'],
                ]);
            }

            // Step 2: Generate unique external ID
            $externalId = 'DON-'.strtoupper(Str::random(8)).'-'.time();

            // Step 3: Create transaction record
            Transaction::create([
                'donor_id' => $donor->id,
                'user_id' => null, // Public donation, no user
                'category_id' => $data['category_id'],
                'amount' => $data['amount'],
                'desc' => $data['notes'] ?? 'Donasi Online',
                'transaction_date' => now(),
                'payment_method' => Transaction::TRANSFER_METHOD,
                'status' => Transaction::COMPLETED_STATUS,
                'reference_number' => $externalId,
            ]);

            // Step 4: Create Xendit invoice
            Configuration::setXenditKey(config('services.xendit.secret_key'));
            $apiInstance = new InvoiceApi;

            $createInvoice = new CreateInvoiceRequest([
                'external_id' => $externalId,
                'amount' => (float) $data['amount'],
                'description' => 'Donasi untuk Mushola - '.$donor->name,
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => $donor->name,
                    'phone' => $donor->phone ?? 'noreply@mushola.com',
                ],
                'currency' => 'IDR',
                'success_redirect_url' => route('success-payment'),
                'failure_redirect_url' => route('donation.index'),
            ]);

            $invoice = $apiInstance->createInvoice($createInvoice);

            DB::commit();

            // Redirect to Xendit payment page
            $this->redirect(url: $invoice['invoice_url']);
        } catch (\Xendit\XenditSdkException $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal Membuat Invoice')
                ->body('Terjadi kesalahan saat menghubungi payment gateway: ')
                ->danger()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal Membuat Donasi')
                ->body('Terjadi kesalahan: ')
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
