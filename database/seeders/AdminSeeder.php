<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin (Platform Owner)
        $admin = User::updateOrCreate(
            ['email' => 'admin@sutra.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Role (Global, so no team_id needed)
        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }
    }
}
