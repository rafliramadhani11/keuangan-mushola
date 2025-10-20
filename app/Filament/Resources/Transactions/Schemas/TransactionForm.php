<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionForm
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
                            ->required(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->required()
                                    ->prefix('Rp')
                                    ->currencyMask('.', ',', 2),

                                Select::make('payment_method')
                                    ->options([
                                        Transaction::CASH_METHOD => 'Cash',
                                        Transaction::TRANSFER_METHOD => 'Transfer',
                                    ])
                                    ->native(false)
                                    ->required(),
                            ])->columnSpanFull(),

                        Textarea::make('desc')
                            ->autosize()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('reference_number')
                            ->hint('(Transfer method)')
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->columnSpan(2),

                //  Content Section kanan
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Pembuat'),
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('created_at'),
                                        TextEntry::make('updated_at'),
                                    ])->columnSpanFull(),
                            ])->columns(2)
                            ->columnSpan(2)
                            ->visible(fn ($operation) => $operation === 'edit'),

                        Section::make()
                            ->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->native(false)
                                    ->required(),

                                Grid::make(2)
                                    ->schema([
                                        DatePicker::make('transaction_date')
                                            ->required()
                                            ->native(false)
                                            ->default(now()),

                                        Select::make('status')
                                            ->options([
                                                Transaction::PENDING_STATUS => 'Pending',
                                                Transaction::COMPLETED_STATUS => 'Completed',
                                                Transaction::FAILED_STATUS => 'Failed',
                                                Transaction::CANCELLED_STATUS => 'Cancelled',
                                            ])
                                            ->native(false)
                                            ->required(),
                                    ])->columnSpanFull(),
                            ])->columns(2)
                            ->columnSpan(2),
                    ])->columnSpan(2),

            ])->columns(4);
    }
}
