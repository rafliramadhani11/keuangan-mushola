<?php

namespace App\Livewire\Dashboard\Report;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;

class SummaryCardsSection extends Component
{
    #[Url]
    public ?string $startDate;

    #[Url]
    public ?string $endDate;

    public function mount()
    {
        $this->startDate = request()->query('startDate');

        $this->endDate = request()->query('endDate');
    }

    public function transactionIncomeQuery()
    {
        return Transaction::query()
            ->with(['donor', 'category', 'user'])
            ->whereHas('category', fn ($q) => $q->where('type', 'income'))
            ->where('status', Transaction::COMPLETED_STATUS)
            ->when($this->startDate, fn ($query) => $query->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('created_at', '<=', $this->endDate));
    }

    public function transactionExpenseQuery()
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
        $totalIncome = $this->transactionIncomeQuery()->sum('amount');

        $totalExpense = $this->transactionExpenseQuery()->sum('amount');

        $balance = $totalIncome - $totalExpense;

        return view('livewire.dashboard.report.summary-cards-section', compact('balance'));
    }
}
