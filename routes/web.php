<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Donation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/donation', Donation::class)->name('donation.index');

Route::get('/success-payment', fn () => view('payment.success'))->name('success-payment');

Route::controller(ReportController::class)->group(function () {
    Route::get('app/report', 'index')->name('dashboard.report');

    Route::get('categories/report', 'categoryReport')->name('category.report');

    Route::get('donors/report', 'donorReport')->name('donor.report');
});
