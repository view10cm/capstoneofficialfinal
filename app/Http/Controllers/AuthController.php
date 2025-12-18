<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check user role and redirect accordingly
            $user = Auth::user();
            
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'Staff') {
                // Add staff dashboard route here when ready
                return redirect()->route('staff.dashboard');
            } elseif ($user->role === 'Kitchen') {
                // Add kitchen dashboard route here when ready
                return redirect()->route('kitchen.dashboard');
            } else {
                // Default for customers
                return redirect()->route('customer.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}