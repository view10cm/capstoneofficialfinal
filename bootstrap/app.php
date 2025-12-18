<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register alias for customer, staff, and kitchen middleware
        $middleware->alias([
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'staff' => \App\Http\Middleware\StaffMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'kitchen' => \App\Http\Middleware\KitchenMiddleware::class, // Added kitchen middleware
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
