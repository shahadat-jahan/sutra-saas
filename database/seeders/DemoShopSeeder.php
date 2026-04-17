<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use App\Enums\BusinessType;
use App\Enums\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Demo Shop
        $shop = Shop::updateOrCreate(
            ['slug' => 'demo-shop'],
            [
                'name' => 'Demo Retail Shop',
                'business_type' => BusinessType::RETAIL,
                'plan' => Plan::BASIC, 
                'status' => 1,
            ]
        );

        // 2. Create Shop Owner
        $owner = User::updateOrCreate(
            ['email' => 'owner@demo.com'],
            [
                'name' => 'Demo Owner',
                'password' => Hash::make('password'),
                'shop_id' => $shop->id,
                'email_verified_at' => now(),
                'status' => 1,
            ]
        );

        // Set Team Context for Spatie Permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($shop->id);

        // Assign Shop Owner Role for this specific shop
        if (!$owner->hasRole('shop-owner')) {
            $owner->assignRole('shop-owner');
        }
    }
}
