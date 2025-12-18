<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has Customer role
        if (Auth::user()->role !== 'Customer') {
            // Redirect based on role
            $role = Auth::user()->role;
            switch ($role) {
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'Staff':
                    return redirect()->route('staff.dashboard');
                case 'Kitchen':
                    return redirect()->route('kitchen.dashboard');
                default:
                    abort(403, 'Unauthorized access.');
            }
        }

        return $next($request);
    }
}