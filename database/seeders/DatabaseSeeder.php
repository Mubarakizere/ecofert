<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ecofert.com'],
            [
                'name' => 'System Administrator',
                'role_type' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'officer@ecofert.com'],
            [
                'name' => 'Extension Officer',
                'role_type' => 'Extension Officer',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'household@ecofert.com'],
            [
                'name' => 'Household User',
                'role_type' => 'Household',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
