<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Allow Staff and Admin (Admin might need to access staff features)
        if ($user->role === 'Staff' || $user->role === 'Admin') {
            return $next($request);
        }

        // Redirect to appropriate dashboard based on role
        return match($user->role) {
            'Customer' => redirect()->route('customer.landingPage'),
            'Kitchen' => redirect()->route('kitchen.dashboard'),
            default => redirect()->route('login')->withErrors([
                'email' => 'You do not have permission to access this area.',
            ]),
        };
    }
}