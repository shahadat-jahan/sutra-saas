<?php

namespace Database\Seeders;

use App\Modules\Shared\Database\Seeders\AdminSeeder;
use App\Modules\Shared\Database\Seeders\AnnouncementSeeder;
use App\Modules\Shared\Database\Seeders\DemoShopSeeder;
use App\Modules\Shared\Database\Seeders\PlanSeeder;
use App\Modules\Shared\Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
            PlanSeeder::class,
            DemoShopSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
