<?php

namespace App\Modules\Shared\Database\Seeders;

use App\Modules\Shared\Domain\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Sutra Basic',
                'slug' => 'basic',
                'price_bdt' => 1500, // Matches POS module
                'price_usd' => 15,
                'features' => ['inventory'],
            ],
            [
                'name' => 'Sutra Pro',
                'slug' => 'pro',
                'price_bdt' => 2200, // POS (1500) + Inventory (700)
                'price_usd' => 25,
                'features' => ['pos', 'inventory', 'basic_reports'],
            ],
            [
                'name' => 'Sutra Enterprise',
                'slug' => 'enterprise',
                'price_bdt' => 3100, // POS (1500) + Inventory (700) + Finance (900)
                'price_usd' => 45,
                'features' => ['pos', 'inventory', 'finance', 'customization', 'basic_reports'],
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                [
                    'uuid' => Str::uuid(),
                    'name' => $planData['name'],
                    'price_bdt' => $planData['price_bdt'],
                    'price_usd' => $planData['price_usd'],
                    'features' => $planData['features'],
                ]
            );
        }
    }
}
