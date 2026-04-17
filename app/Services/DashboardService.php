<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Shop;
use App\Models\User;

final class DashboardService
{
    /**
     * Get admin dashboard statistics.
     *
     * @return array<string, int>
     */
    public function getAdminStats(): array
    {
        return [
            'total_shops' => Shop::count(),
            'total_users' => User::count(),
            'active_shops' => Shop::where('status', 1)->count(),
        ];
    }
}

