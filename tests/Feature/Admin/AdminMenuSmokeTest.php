<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminMenuSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_menu_pages_render_for_super_admin(): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        $role = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
            'team_id' => null,
        ]);

        /** @var User $user */
        $user = User::factory()->create([
            'shop_id' => null,
            'email_verified_at' => now(),
            'status' => 1,
        ]);
        $user->assignRole($role);

        $host = config('app.domain', 'localhost');

        $this->actingAs($user)
            ->withServerVariables(['HTTP_HOST' => $host])
            ->get(route('admin.dashboard', absolute: false))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Dashboard'));

        $this->actingAs($user)
            ->withServerVariables(['HTTP_HOST' => $host])
            ->get(route('admin.announcements.index', absolute: false))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Announcements/Index'));

        $this->actingAs($user)
            ->withServerVariables(['HTTP_HOST' => $host])
            ->get(route('admin.announcements.create', absolute: false))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Announcements/Create'));

        $this->actingAs($user)
            ->withServerVariables(['HTTP_HOST' => $host])
            ->get(route('admin.shops.index', absolute: false))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Shops/Index'));

        $this->actingAs($user)
            ->withServerVariables(['HTTP_HOST' => $host])
            ->get(route('admin.users.index', absolute: false))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Users/Index'));

        $this->actingAs($user)
            ->withServerVariables(['HTTP_HOST' => $host])
            ->get(route('admin.settings.index', absolute: false))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Settings/Index'));
    }
}
