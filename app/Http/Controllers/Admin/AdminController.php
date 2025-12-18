<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('admin.adminUsers', compact('users'));
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