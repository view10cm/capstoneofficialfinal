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

        // Optionally create other test users
        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'Customer',
        ]);
    }
}