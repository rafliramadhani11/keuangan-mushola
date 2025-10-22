<?php

use App\Http\Controllers\XenditWebhookController;
use App\Livewire\Donation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/donation', Donation::class)->name('donation');

// Donation Success/Failed Pages
Route::get('/donation/success', function () {
    return view('donation.success');
})->name('donation.success');

Route::get('/donation/failed', function () {
    return view('donation.failed');
})->name('donation.failed');

// Xendit Webhook (already excluded from CSRF in bootstrap/app.php)
Route::post('/xendit/webhook', [XenditWebhookController::class, 'handle'])
    ->name('xendit.webhook');
