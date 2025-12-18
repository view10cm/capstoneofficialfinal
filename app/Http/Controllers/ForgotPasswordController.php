<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\PasswordResetMail;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form
     */
    public function show()
    {
        return view('forgot-password');
    }

    /**
     * Handle the forgot password request
     */
    public function sendResetLink(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'The email address is not registered in our system.'
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate a reset token
        $token = Str::random(64);
        $email = $request->email;
        
        // Create reset URL
        $resetUrl = URL::temporarySignedRoute(
            'password.reset',
            now()->addHours(24),
            ['token' => $token, 'email' => $email]
        );

        // Save or update the token in database
        PasswordResetToken::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        try {
            // Send the email
            Mail::to($email)->send(new PasswordResetMail($resetUrl));
            
            return back()->with('status', 'Password reset link has been sent to your email!');
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reset email. Please try again later.');
        }
    }
    
    /**
     * Show reset password form
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');
        
        // Verify the token exists and is valid (not expired - check within 24 hours)
        $tokenRecord = PasswordResetToken::where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->first();
            
        if (!$tokenRecord) {
            return redirect()->route('forgot-password')
                ->with('error', 'Invalid or expired reset link.');
        }
        
        return view('reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }
    
    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Verify the token is still valid
        $tokenRecord = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->first();
            
        if (!$tokenRecord) {
            return redirect()->route('forgot-password')
                ->with('error', 'Invalid or expired reset link.');
        }
        
        // Update user's password
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        
        // Delete the used token
        $tokenRecord->delete();
        
        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully!');
    }
}