<?php

namespace App\Livewire\Dashboard\Report;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Livewire\Component;

class ExpenseTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public string $startDate;

    public string $endDate;

    public function mount(string $startDate, string $endDate): void
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->transactionQuery())
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->heading('📉 Rincian Pengeluaran')
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('j F Y ')),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('desc')
                    ->label('Keterangan'),
                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->formatStateUsing(fn ($state) => Str::ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        Transaction::CASH_METHOD => 'success',
                        Transaction::TRANSFER_METHOD => 'info',
                        default => 'gray'
                    })
                    ->badge(),
                TextColumn::make('user.name')
                    ->label('Penanggung Jawab'),
                TextColumn::make('amount')
                    ->currency('IDR')
                    ->summarize(
                        Sum::make()
                            ->query(fn (Builder $query) => $query->where('status', Transaction::COMPLETED_STATUS))
                            ->label('Total Pengeluaran')
                            ->money('IDR')
                    ),
                TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('j F Y ')),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
    }

    public function transactionQuery()
    {
        return Transaction::query()
            ->with(['donor', 'category', 'user'])
            ->whereHas('category', fn ($q) => $q->where('type', 'expense'))
            ->where('status', Transaction::COMPLETED_STATUS)
            ->when($this->startDate, fn ($query) => $query->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('created_at', '<=', $this->endDate));
    }

    public function render()
    {
        return view('livewire.dashboard.report.expense-table');
    }
}
