<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '1234567890',
            'address' => '123 Admin Street, Admin City',
            'email_verified_at' => now(),
        ]);

        // Create Doctor Users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Doctor $i",
                'email' => "doctor$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => "1234567$i",
                'address' => "123 Doctor Street, Doctor City $i",
                'email_verified_at' => now(),
            ]);
        }

        // Create Patient Users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Patient $i",
                'email' => "patient$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => "9876543$i",
                'address' => "123 Patient Street, Patient City $i",
                'email_verified_at' => now(),
            ]);
        }
    }
}
