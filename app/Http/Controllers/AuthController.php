<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        // Check if user exists first
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Account does not exist. Please check your email or register.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();

            // Redirect based on role
            return match ($user->role) {
                'Admin' => redirect()->route('admin.dashboard'),
                'Customer' => redirect()->route('customer.landingPage'),
                'Staff' => redirect()->route('staff.landingPage'),
                'Kitchen' => redirect()->route('kitchen.dashboard'),
                default => redirect()->route('login')->withErrors([
                    'email' => 'Unauthorized role.',
                ]),
            };
        }

        return back()->withErrors([
            'email' => 'Incorrect password. Please try again.',
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