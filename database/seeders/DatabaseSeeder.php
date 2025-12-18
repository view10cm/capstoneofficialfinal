<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@caffearabica.com',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'rednegrite@gmail.com',
            'password' => Hash::make('View10cm'),
            'role' => 'Admin',
        ]);

        // Create Staff Account
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@caffearabica.com',
            'password' => Hash::make('staff123'),
            'role' => 'Staff',
        ]);

        // Create Kitchen Staff Account - ALREADY EXISTS
        User::create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@caffearabica.com',
            'password' => Hash::make('kitchen123'),
            'role' => 'Kitchen',
        ]);

        // Create Customer Account
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@caffearabica.com',
            'password' => Hash::make('customer123'),
            'role' => 'Customer',
        ]);

        // Optionally create other test users
        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'Customer',
        ]);
    }
}