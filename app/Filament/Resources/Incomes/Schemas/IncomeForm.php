<?php

namespace App\Filament\Resources\Incomes\Schemas;

use App\Models\Category;
use App\Models\Donor;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;

class IncomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Content Section Kiri
                Section::make()
                    ->schema([
                        Select::make('donor_id')
                            ->label('Nama Donatur')
                            ->relationship('donor', 'name')
                            ->native(false)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->hint('(optional)')
                                    ->label('Email address')
                                    ->email(),
                                TextInput::make('phone')
                                    ->hint('(optional)')
                                    ->belowContent('ex: 898 1332 4231')
                                    ->prefix('+62')
                                    ->mask('999 9999 9999 9999'),
                                Select::make('type')
                                    ->options([
                                        Donor::INDIVIDUAL => 'Per Orangan',
                                        Donor::ORGANIZATION => 'Organisasi',
                                    ])
                                    ->native(false)
                                    ->required(),
                            ])
                            ->createOptionAction(
                                fn (Action $action) => $action
                                    ->modalWidth(Width::Large)
                                    ->modalHeading('Buat Donatur')
                                    ->after(
                                        fn () => Notification::make()
                                            ->success()
                                            ->title('Data berhasil dibuat')
                                            ->send()
                                    )
                                    ->tooltip('Buat donatur')
                            )
                            ->columnSpanFull(),

                        Select::make('category_id')
                            ->relationship('category', 'name', fn (Builder $query) => $query->where('type', Category::INCOME))
                            ->native(false)
                            ->required()
                            ->columnStart(1),

                        TextInput::make('amount')
                            ->required()
                            ->prefix('Rp')
                            ->currencyMask('.', ',', 2),

                        Textarea::make('desc')
                            ->autosize()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('reference_number')
                            ->hint('(From payment gateway)')
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($state) => $state),

                    ])->columns(2)
                    ->columnSpan(2),

                //  Content Section kanan
                Grid::make()
                    ->schema([
                        // Created at & Updated At
                        Section::make()
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Pembuat')
                                    ->visible(fn ($record) => ! $record->reference_number),
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('created_at'),
                                        TextEntry::make('updated_at'),
                                    ])->columnSpanFull(),
                            ])->columns(2)
                            ->columnSpanFull()
                            ->visible(fn ($operation) => $operation === 'edit'),

                        // Info Transaction
                        Section::make()
                            ->schema([
                                Select::make('payment_method')
                                    ->options([
                                        Transaction::CASH_METHOD => 'Cash',
                                        Transaction::TRANSFER_METHOD => 'Transfer',
                                    ])
                                    ->required()
                                    ->native(false),
                                DatePicker::make('transaction_date')
                                    ->required()
                                    ->native(false)
                                    ->default(now())
                                    ->columnStart(1),
                                Select::make('status')
                                    ->options([
                                        Transaction::PENDING_STATUS => 'Pending',
                                        Transaction::COMPLETED_STATUS => 'Completed',
                                        Transaction::FAILED_STATUS => 'Failed',
                                        Transaction::CANCELLED_STATUS => 'Cancelled',
                                    ])
                                    ->native(false)
                                    ->required(),
                            ])->columns(2)
                            ->columnSpanFull(),
                    ])->columnSpan(2),

            ])->columns(4);
    }
}
