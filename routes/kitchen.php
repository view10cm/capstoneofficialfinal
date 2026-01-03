<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KitchenController;

// Kitchen routes
Route::middleware(['web', 'auth', 'kitchen'])->group(function () {
    Route::get('/kitchen/dashboard', [KitchenController::class, 'dashboard'])->name('kitchen.dashboard');
    Route::post('/kitchen/update-cooking-status', [KitchenController::class, 'updateCookingStatus'])->name('kitchen.update-cooking-status');
});

Route::get('/kitchen/completed-orders', [KitchenController::class, 'completedOrders'])->name('kitchen.completed-orders');