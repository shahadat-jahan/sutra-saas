<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Shop;
use App\Models\User;
use App\Support\Dashboard;
use Tests\TestCase;

class DashboardUrlTest extends TestCase
{
    public function test_url_for_platform_user_without_shop_points_to_admin_dashboard(): void
    {
        $user = new User;

        $this->assertSame('/admin/dashboard', Dashboard::urlFor($user));
    }

    public function test_url_for_tenant_user_with_shop_points_to_subdomain_dashboard(): void
    {
        $user = new User;
        $shop = new Shop(['slug' => 'acme']);
        $user->setRelation('shop', $shop);

        $url = Dashboard::urlFor($user);

        $this->assertStringContainsString('acme.', $url);
        $this->assertStringEndsWith('/dashboard', $url);
    }
}
