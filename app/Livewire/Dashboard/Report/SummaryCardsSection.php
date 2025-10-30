<?php

namespace App\Livewire\Dashboard\Report;

use App\Models\Transaction;
use Livewire\Component;

class SummaryCardsSection extends Component
{
    public string $startDate;

    public string $endDate;

    public function mount(string $startDate, string $endDate): void
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
