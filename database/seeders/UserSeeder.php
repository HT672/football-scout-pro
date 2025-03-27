<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Second Admin',
            'email' => 'hadi@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Create scout user
        User::create([
            'name' => 'Scout User',
            'email' => 'scout@example.com',
            'password' => bcrypt('password'),
            'role' => 'scout',
        ]);

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }
}