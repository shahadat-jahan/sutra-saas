<?php

namespace Database\Seeders;

use App\Models\Shop;
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
        $shop = Shop::create([
            'name' => 'Demo Shop',
            'business_type' => 'retail',
        ]);

        User::factory()->create([
            'name' => 'Shop Owner',
            'email' => 'owner@demo.com',
            'password' => Hash::make('password'),
            'shop_id' => $shop->id,
        ]);
    }
}
