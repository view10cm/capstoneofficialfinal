<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Models\VoiceTranscript; // Add this line

// Customer routes - protected by customer middleware
Route::middleware(['auth', 'customer'])->group(function () {
    // Customer Landing Page
    Route::get('/customer/home', [CustomerController::class, 'landingPage'])
        ->name('customer.landingPage');

    // Customer Dashboard
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
        ->name('customer.dashboard');

    // Customer Notification Page
    Route::get('/customer/notification', [CustomerController::class, 'notification'])
        ->name('customer.notification');

    // Customer Order Area Page
    Route::get('/customer/order-area', [CustomerController::class, 'orderArea'])
        ->name('customer.orderArea');

    // AJAX route for filtering products
    Route::post('/customer/get-products', [CustomerController::class, 'getProductsByCategory'])
        ->name('customer.getProducts');

    // AJAX route for getting specific slide content
    Route::post('/customer/get-slide', [CustomerController::class, 'getSlideContent'])
        ->name('customer.getSlide');

    // AJAX route for saving order to staff transaction
    Route::post('/customer/save-order', [CustomerController::class, 'saveOrderToStaffTransaction'])
        ->name('customer.saveOrder');
        
    // NEW: AJAX route for saving voice transcripts
    Route::post('/customer/save-transcript', [CustomerController::class, 'saveVoiceTranscript'])
        ->name('customer.saveTranscript');
});