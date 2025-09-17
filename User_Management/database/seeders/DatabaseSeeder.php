<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        if (!User::where('email', 'test@example.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
            ]);
        }

        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::create([
                'name' => 'Main Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('securepassword'),
            ]);
        }
    }
}