<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopUserRequest;
use App\Http\Requests\Admin\UpdateShopUserRequest;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\PlatformAccessNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShopUserController extends Controller
{
    public function index(Shop $shop): Response
    {
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        return Inertia::render('Admin/Shops/Users/Index', [
            'shop' => [
                'id' => $shop->id,
                'uuid' => $shop->uuid,
                'name' => $shop->name,
                'slug' => $shop->slug,
            ],
            'users' => User::query()
                ->where('shop_id', $shop->id)
                ->with('roles')
                ->latest()
                ->get()
                ->map(fn (User $user) => [
                    'id' => $user->id,
                    'uuid' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at?->toDateTimeString(),
                    'roles' => $user->roles->map(fn ($r) => ['id' => $r->id, 'name' => $r->name]),
                ]),
            'roles' => Role::query()
                ->where(fn ($q) => $q->whereNull($teamsKey)->orWhere($teamsKey, $shop->id))
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(StoreShopUserRequest $request, Shop $shop): RedirectResponse
    {
        $data = $request->validated();
        $password = $data['password'] ?? Str::password(length: 14);

        $user = User::create([
            'shop_id' => $shop->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make((string) $password),
            'status' => 1,
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($shop->id);
        $user->assignRole($data['role']);

        $scheme = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http';
        $tenantUrl = sprintf('%s://%s.%s/dashboard', $scheme, $shop->slug, (string) config('app.domain', 'localhost'));

        $user->notify(new PlatformAccessNotification(
            appName: (string) config('app.name', 'Sutra'),
            loginUrl: rtrim((string) config('app.url', 'http://localhost'), '/') . '/login',
            tenantUrl: $tenantUrl,
            email: $user->email,
            password: (string) $password,
            roleName: (string) $data['role'],
        ));

        return back()->with('success', 'User created successfully.');
    }

    public function update(UpdateShopUserRequest $request, Shop $shop, User $user): RedirectResponse
    {
        if ((int) $user->shop_id !== (int) $shop->id) {
            abort(404);
        }

        $data = $request->validated();

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make((string) $data['password']);
        }

        $user->update($payload);

        app(PermissionRegistrar::class)->setPermissionsTeamId($shop->id);
        $user->syncRoles([(string) $data['role']]);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(Shop $shop, User $user): RedirectResponse
    {
        if ((int) $user->shop_id !== (int) $shop->id) {
            abort(404);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}

