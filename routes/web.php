<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Donation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/donation', Donation::class)->name('donation.index');

Route::get('/success-payment', fn () => view('payment.success'))->name('success-payment');

Route::get('app/report', [ReportController::class, 'index'])->name('dashboard.report');
