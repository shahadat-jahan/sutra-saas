<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Tenant;

use App\Modules\Shared\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index(): Response
    {
        /** @var \App\Modules\Shared\Domain\Models\User $user */
        $user = auth()->user();
        $shop = $user->shop;
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        return Inertia::render('Tenant/Roles/Index', [
            'roles' => Role::query()
                ->where(fn ($q) => $q->whereNull($teamsKey)->orWhere($teamsKey, $shop->id))
                ->with('permissions')
                ->orderBy('name')
                ->get()
                ->map(fn (Role $role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'team_id' => $role->team_id ?? null,
                    'permissions' => $role->permissions->pluck('name'),
                ]),
            'permissions' => Permission::query()->orderBy('name')->pluck('name'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Modules\Shared\Domain\Models\User $user */
        $user = auth()->user();
        $shop = $user->shop;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId((string) $shop->id);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        /** @var \App\Modules\Shared\Domain\Models\User $user */
        $user = auth()->user();
        $shop = $user->shop;
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        if (is_null($role->{$teamsKey})) {
            return back()->with('error', 'Global roles cannot be edited.');
        }

        if ((string) $role->{$teamsKey} !== (string) $shop->id) {
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

    public function destroy(Role $role): RedirectResponse
    {
        /** @var \App\Modules\Shared\Domain\Models\User $user */
        $user = auth()->user();
        $shop = $user->shop;
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        if (is_null($role->{$teamsKey})) {
            return back()->with('error', 'Global roles cannot be deleted.');
        }

        if ((string) $role->{$teamsKey} !== (string) $shop->id) {
            abort(404);
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
