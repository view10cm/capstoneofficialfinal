<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        return view('admin.adminDashboard');
    }

    /**
     * Display the inventory management page.
     */
    public function inventory()
    {
        return view('admin.adminInventory');
    }

    /**
     * Display the menu management page.
     */
    public function menu()
    {
        return view('admin.adminMenu');
    }

    /**
     * Display the users management page.
     */
    public function users()
    {
        // Get users with pagination (7 per page as shown in the image)
        $users = User::paginate(7);
    
        // Get counts for statistics
        $activatedCount = User::where('status', 'Activated')->count();
        $deactivatedCount = User::where('status', 'Deactivated')->count();
    
        return view('admin.adminUsers', compact('users', 'activatedCount', 'deactivatedCount'));
    }

    /**
     * Display the order history page.
     */
    public function orderHistory()
    {
        return view('admin.adminOrderHistory');
    }

    /**
     * Update user status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Activated,Deactivated'
        ]);

        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }

    /**
     * Create a new user.
     */
    public function createUser(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the user with Staff role and Activated status
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Staff', // Default role for new users
            'status' => 'Activated', // Default status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'last_login' => null,
            ]
        ]);
    }

    /**
     * Update user's last login time.
     * This should be called when a user logs in.
     */
    public function updateLastLogin($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->last_login = now();
            $user->save();
        }
    }
}