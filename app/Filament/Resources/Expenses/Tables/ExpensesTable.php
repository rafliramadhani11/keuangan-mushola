<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Models\Transaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->expenseData())
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => Str::ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        Transaction::COMPLETED_STATUS => 'success',
                        Transaction::PENDING_STATUS => 'warning',
                        Transaction::FAILED_STATUS => 'danger',
                        Transaction::CANCELLED_STATUS => 'danger',
                    })
                    ->badge(),
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->currency('IDR')
                    ->summarize(
                        Sum::make()
                            ->query(fn (Builder $query) => $query->where('status', Transaction::COMPLETED_STATUS))
                            ->label('Total Pengeluaran')
                            ->money('IDR')
                    )
                    ->sortable(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
