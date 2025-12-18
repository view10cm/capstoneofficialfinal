<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register admin routes
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));

        // Register customer routes
        Route::middleware('web')
            ->group(base_path('routes/customer.php'));
    }
}