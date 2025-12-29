<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display customer landing page
     */
    public function landingPage()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerLandingPage');
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customerDashboard');
    }

    /**
     * Display customer notification page
     */
    public function notification()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerNotification');
    }

    /**
     * Display customer order area page
     */
    public function orderArea()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerOrderArea');
    }
}