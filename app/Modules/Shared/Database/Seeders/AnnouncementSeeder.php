<?php

declare(strict_types=1);

namespace App\Modules\Shared\Database\Seeders;

use App\Modules\Shared\Domain\Models\Announcement;
use App\Modules\Shared\Domain\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@sutra.com')->first();

        Announcement::updateOrCreate(
            ['title' => 'Welcome to Sutra'],
            [
                'user_id' => $admin?->id,
                'body' => 'We are live. Expect frequent improvements to the platform admin, tenant dashboards, and analytics.',
                'published_at' => now(),
            ]
        );
    }
}
