<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use App\Enums\BusinessType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Setup Roles
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Create Super Admin (Platform Owner)
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@sutra.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('super-admin');

        // 3. Create a Demo Shop
        $shop = Shop::create([
            'name' => 'Demo Retail Shop',
            'slug' => 'demo-shop',
            'business_type' => BusinessType::RETAIL->value,
            'status' => 1,
            'enabled_modules' => ['pos'],
        ]);

        // 4. Create Shop Owner
        $owner = User::create([
            'name' => 'Demo Owner',
            'email' => 'owner@demo.com',
            'password' => Hash::make('password'),
            'shop_id' => $shop->id,
            'email_verified_at' => now(),
            'status' => 1,
        ]);
        $owner->assignRole('shop-owner');
    }
}
