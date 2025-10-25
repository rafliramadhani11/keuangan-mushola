<?php

use App\Livewire\Donation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/donation', Donation::class)->name('donation.index');

Route::get('/success-payment', fn() => view('payment.success'))->name('success-payment');

Route::get('app/report', function (Request $request) {
    $startDate = $request->query('startDate');
    $endDate = $request->query('endDate');

    // dd($startDate, $endDate);

    // Get income transactions
    $incomeTransactions = Transaction::query()
        ->with(['donor', 'category', 'user'])
        ->whereHas('category', fn($q) => $q->where('type', 'income'))
        ->where('status', Transaction::COMPLETED_STATUS)
        ->whereBetween('transaction_date', [$startDate, $endDate])
        ->orderBy('transaction_date', 'asc')
        ->get();

    // Get expense transactions
    $expenseTransactions = Transaction::query()
        ->with(['category', 'user'])
        ->whereHas('category', fn($q) => $q->where('type', 'expense'))
        ->where('status', Transaction::COMPLETED_STATUS)
        ->whereBetween('transaction_date', [$startDate, $endDate])
        ->orderBy('transaction_date', 'asc')
        ->get();

    // Calculate totals
    $totalIncome = $incomeTransactions->sum('amount');
    $totalExpense = $expenseTransactions->sum('amount');
    $balance = $totalIncome - $totalExpense;

    return view('dashboard.report', [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'incomeTransactions' => $incomeTransactions,
        'expenseTransactions' => $expenseTransactions,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
        'balance' => $balance,
        'incomeCount' => $incomeTransactions->count(),
        'expenseCount' => $expenseTransactions->count(),
    ]);
})->name('dashboard.report');
