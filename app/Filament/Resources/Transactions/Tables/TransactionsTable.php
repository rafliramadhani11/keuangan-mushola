<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Transaction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('donor.name')
                    ->label('Nama Donatur')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->currency('IDR')
                    ->sortable(),
                TextColumn::make('desc')
                    ->searchable(),
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->formatStateUsing(fn ($state) => Str::ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        Transaction::CASH_METHOD => 'success',
                        Transaction::TRANSFER_METHOD => 'warning',
                    })
                    ->badge(),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => Str::ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        Transaction::COMPLETED_STATUS => 'success',
                        Transaction::PENDING_STATUS => 'warning',
                        Transaction::FAILED_STATUS => 'danger',
                        Transaction::CANCELLED_STATUS => 'danger',
                    })
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])->icon(Heroicon::EllipsisHorizontal),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
