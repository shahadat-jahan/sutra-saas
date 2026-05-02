<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'price_bdt' => 500,
                'price_usd' => 8,
                'features' => ['pos'],
            ],
            [
                'name' => 'Sutra Pro',
                'slug' => 'pro',
                'price_bdt' => 1000,
                'price_usd' => 15,
                'features' => ['pos', 'inventory', 'basic_reports'],
            ],
            [
                'name' => 'Sutra Enterprise',
                'slug' => 'enterprise',
                'price_bdt' => 0, // Custom
                'price_usd' => 0, // Custom
                'features' => ['pos', 'inventory', 'finance', 'customization', 'basic_reports'],
            ],
        ];

        foreach ($plans as $planData) {
            \App\Models\Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                [
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'name' => $planData['name'],
                    'price_bdt' => $planData['price_bdt'],
                    'price_usd' => $planData['price_usd'],
                    'features' => $planData['features'],
                ]
            );
        }
    }
}
