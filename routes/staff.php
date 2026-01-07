<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffOrderController;

Route::middleware(['auth', 'staff'])->group(function () {
    
    // Staff pages
    Route::get('/staff/landing-page', function () {
        return view('staffLandingPage');
    })->name('staff.landingPage');
    
    Route::get('/staff/order-tracker', function () {
        return view('staffOrderTracker');
    })->name('staff.order-tracker');
    
    // API routes
    Route::prefix('api/staff')->group(function () {
        // Order operations
        Route::get('/orders', [StaffOrderController::class, 'getOrders']);
        Route::post('/orders/update-all-status', [StaffOrderController::class, 'updateAllStatus']);
        Route::post('/orders/cancel', [StaffOrderController::class, 'cancelOrder']);
        Route::post('/orders/void-products', [StaffOrderController::class, 'voidProducts']);
        Route::post('/orders/save-payment-transaction', [StaffOrderController::class, 'savePaymentTransaction']);
        Route::post('/orders/add-products', [StaffOrderController::class, 'addProducts']);
        
        // Menu products
        Route::get('/menu/products', [StaffOrderController::class, 'getMenuProducts']);
        
        // Order tracker data
        Route::get('/order-tracker', [StaffOrderController::class, 'getOrderTrackerData']);
    });
});