<?php

use App\Livewire\Donation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/donation', Donation::class)->name('donation.index');

Route::get('/success-payment', function () {
    return view('payment.success');
})->name('success-payment');
