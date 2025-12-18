<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KitchenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has kitchen role
        if (!auth()->check() || auth()->user()->role !== 'Kitchen') {
            abort(403, 'Unauthorized access. Kitchen role required.');
        }

        return $next($request);
    }
}