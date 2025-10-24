<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected int|array|null $columns = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        return [
            Stat::make(
                label: 'Total Pemasukan',
                value: 'Rp '.$this->incomeTotal($startDate, $endDate)
            ),
            Stat::make(
                label: 'Total Pengeluaran',
                value: 'Rp '.$this->expenseTotal($startDate, $endDate)
            ),
        ];
    }

    public function incomeTotal($startDate, $endDate)
    {
        $amountTotal = $this->transactions()
            ->incomeData()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('amount');

        return number_format($amountTotal, 2, ',', '.');
    }

    public function expenseTotal($startDate, $endDate)
    {
        $amountTotal = $this->transactions()
            ->expenseData()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('amount');

        return number_format($amountTotal, 2, ',', '.');
    }

    #[Computed()]
    public function transactions()
    {
        return Transaction::query();
    }
}
