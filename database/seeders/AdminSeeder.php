<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if an admin already exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'user_id' => 'ADMIN001',
                'email' => 'admin@example.com',
                'gender' => 'Male',  
                'join_date' => '2024-10-01',
                'phone_number' => '60123456789',  
                'status' => 'active',
                'role_name' => 'Admin',  // Set role as Admin
                'avatar' => 'admin.jpg', 
                'position' => 'Administrator',
                'department' => 'HR',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),  // Password 
                'remember_token' => \Str::random(10),
            ]);
        }
    }
}
