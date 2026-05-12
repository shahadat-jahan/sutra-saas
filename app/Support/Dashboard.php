<?php

declare(strict_types=1);

namespace App\Support;

use App\Modules\Shared\Domain\Models\User;

class Dashboard
{
    /**
     * Returns the best "post-auth" dashboard URL for a given user.
     *
     * - Tenant users (have a shop) go to the tenant subdomain dashboard route.
     * - Platform users (no shop) go to the admin dashboard route.
     */
    public static function urlFor(User $user, array $query = []): string
    {
        if ($user->shop) {
            return route('dashboard', ['subdomain' => $user->shop->slug, ...$query]);
        }

        return route('admin.dashboard', $query, absolute: false);
    }
}
