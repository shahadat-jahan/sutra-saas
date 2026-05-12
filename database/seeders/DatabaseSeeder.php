<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \App\Modules\Shared\Database\Seeders\RolesAndPermissionsSeeder::class,
            \App\Modules\Shared\Database\Seeders\AdminSeeder::class,
            \App\Modules\Shared\Database\Seeders\PlanSeeder::class,
            \App\Modules\Shared\Database\Seeders\DemoShopSeeder::class,
            \App\Modules\Shared\Database\Seeders\AnnouncementSeeder::class,
        ]);
    }
}
