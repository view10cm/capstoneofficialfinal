<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KitchenController;

// Kitchen routes
Route::middleware(['web', 'auth', 'kitchen'])->group(function () {
    Route::get('/kitchen/dashboard', function () {
        return view('kitchenLandingPage');
    })->name('kitchen.dashboard');
    
    // Add more kitchen-specific routes here
    // Example: Route::get('/kitchen/orders', [KitchenController::class, 'showOrders'])->name('kitchen.orders');
});