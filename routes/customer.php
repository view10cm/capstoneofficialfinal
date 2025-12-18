<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

// Customer routes - protected by customer middleware
Route::middleware(['auth', 'customer'])->group(function () {
    // Customer Landing Page
    Route::get('/customer/home', [CustomerController::class, 'landingPage'])
        ->name('customer.landingPage');

    // Customer Dashboard
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
        ->name('customer.dashboard');

    // You can add more customer routes here
});