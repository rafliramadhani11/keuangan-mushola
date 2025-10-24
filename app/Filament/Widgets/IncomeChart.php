<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Livewire\Attributes\Computed;

class IncomeChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Chart Pemasukan & Pengeluaran';

    protected ?string $description = 'Berdasarkan filter yang diterapkan';

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? now()->startOfYear();
        $endDate = $this->pageFilters['endDate'] ?? now()->endOfYear();

        $incomeTrend = $this->transactionTrendQuery('income', $startDate, $endDate);
        $expenseTrend = $this->transactionTrendQuery('expense', $startDate, $endDate);

        $labels = $incomeTrend->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('F'));

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $incomeTrend->map(fn (TrendValue $v) => $v->aggregate)->toArray(),
                    'borderColor' => '#16a34a', // hijau
                    'backgroundColor' => 'rgba(22,163,74,0.2)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $expenseTrend->map(fn (TrendValue $v) => $v->aggregate)->toArray(),
                    'borderColor' => '#dc2626', // merah
                    'backgroundColor' => 'rgba(220,38,38,0.2)',
                    'tension' => 0.3,
                ],
            ],

            'labels' => $labels->toArray(),
        ];
    }

    public function transactionTrendQuery($type, $startDate, $endDate)
    {
        $query = $this->transactionQuery();

        // Filter berdasarkan tipe transaksi
        if ($type === 'income') {
            $query->whereHas('category', fn ($q) => $q->where('type', 'income'));
        } elseif ($type === 'expense') {
            $query->whereHas('category', fn ($q) => $q->where('type', 'expense'));
        }

        return Trend::query($query)
            ->between(
                start: Carbon::parse($startDate)->startOfDay(),
                end: Carbon::parse($endDate)->endOfDay(),
            )
            ->perMonth()
            ->sum('amount');
    }

    #[Computed()]
    public function transactionQuery()
    {
        return Transaction::query()
            ->where('status', 'completed');
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => 'Rp ' + value.toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }),
                    },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                            return label;
                        }
                    }
                }
            },
        }
    JS);
    }

    protected function getType(): string
    {
        return 'line';
    }
}
