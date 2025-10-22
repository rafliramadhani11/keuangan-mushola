<?php

namespace App\Livewire;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
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
                        Toggle::make('is_anonymous')
                            ->label('Sembunyikan Nama')
                            ->inline(false),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));

        $apiInstance = new InvoiceApi;

        $createInvoice = new CreateInvoiceRequest([
            'external_id' => 'test1234',
            'amount' => 10000,
        ]);

        $invoice = $apiInstance->createInvoice($createInvoice);

        $this->redirect(url: $invoice['invoice_url']);
    }

    #[Title('Donation Page')]
    public function render()
    {
        return view('livewire.donation');
    }
}
