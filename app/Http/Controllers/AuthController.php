<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();

            // Redirect based on role
            return match ($user->role) {
                'Admin' => redirect()->route('admin.dashboard'),
                'Customer' => redirect()->route('customer.landingPage'),
                'Staff' => redirect()->route('staff.landingPage'),  // Updated to staff.landingPage
                'Kitchen' => redirect()->route('kitchen.dashboard'),
                default => redirect()->route('login')->withErrors([
                    'email' => 'Unauthorized role.',
                ]),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}