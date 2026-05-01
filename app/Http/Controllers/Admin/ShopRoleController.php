<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShopRoleController extends Controller
{
    public function index(Shop $shop): Response
    {
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        return Inertia::render('Admin/Shops/Roles/Index', [
            'shop' => [
                'id' => $shop->id,
                'uuid' => $shop->uuid,
                'name' => $shop->name,
                'slug' => $shop->slug,
            ],
            'roles' => Role::query()
                ->where(fn ($q) => $q->whereNull($teamsKey)->orWhere($teamsKey, $shop->id))
                ->with('permissions')
                ->orderBy('name')
                ->get()
                ->map(fn (Role $role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'team_id' => $role->team_id ?? null,
                    'permissions' => $role->permissions->map(fn (Permission $p) => ['id' => $p->id, 'name' => $p->name]),
                ]),
            'permissions' => Permission::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(Request $request, Shop $shop): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($shop->id);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Shop $shop, Role $role): RedirectResponse
    {
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';
        if (is_null($role->{$teamsKey})) {
            return back()->with('error', 'Global roles cannot be edited from a shop.');
        }

        if ((int) $role->{$teamsKey} !== (int) $shop->id) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Shop $shop, Role $role): RedirectResponse
    {
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';
        if (is_null($role->{$teamsKey})) {
            return back()->with('error', 'Global roles cannot be deleted from a shop.');
        }

        if ((int) $role->{$teamsKey} !== (int) $shop->id) {
            abort(404);
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
