<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffOrderController;
use App\Http\Controllers\ReceiptController;

Route::middleware(['auth', 'staff'])->group(function () {
    // Staff Landing Page/Dashboard
    Route::get('/staff/dashboard', function () {
        return view('staffLandingPage');
    })->name('staff.dashboard');
    
    Route::get('/staff/landing-page', function () {
        return view('staffLandingPage');
    })->name('staff.landingPage');
    
    // Order Tracker Page
    Route::get('/staff/order-tracker', function () {
        return view('staffOrderTracker');
    })->name('staff.order-tracker');
    
    // API Routes for Staff
    Route::prefix('api/staff')->group(function () {
        // Using StaffOrderController for order operations
        Route::get('/orders', [StaffOrderController::class, 'getOrders'])->name('staff.orders.api');
        Route::post('/orders/update-all-status', [StaffOrderController::class, 'updateAllStatus'])->name('staff.orders.update-all-status');
        Route::post('/orders/cancel', [StaffOrderController::class, 'cancelOrder'])->name('staff.orders.cancel');
        Route::post('/orders/void-products', [StaffOrderController::class, 'voidProducts'])->name('staff.orders.void-products');
        Route::post('/orders/save-payment-transaction', [StaffOrderController::class, 'savePaymentTransaction'])->name('staff.orders.save-payment-transaction');
        
        // Keep original StaffController routes if needed elsewhere
        Route::post('/orders/update-status', [StaffController::class, 'updateStatus'])->name('staff.orders.update-status');
        Route::post('/orders/void', [StaffController::class, 'voidOrder'])->name('staff.orders.void');

        // Receipt generation
        Route::get('/receipt/generate', [ReceiptController::class, 'generateReceipt'])->name('staff.receipt.generate');
        Route::get('/receipt/{transactionId}', [ReceiptController::class, 'generateReceiptFromId'])->name('staff.receipt.from-id');
    });

    Route::get('/api/staff/order-tracker', [StaffOrderController::class, 'getOrderTrackerData'])->name('staff.order-tracker.api');
    
    // Add more staff-specific routes here
    // Route::get('/staff/orders', [StaffController::class, 'viewOrders'])->name('staff.orders');
    // Route::get('/staff/menu', [StaffController::class, 'viewMenu'])->name('staff.menu');
    // etc.
});