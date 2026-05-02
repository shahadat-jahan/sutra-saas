<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShopUpdateRequest;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\PlatformAccessNotification;
use App\Services\ShopService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShopController extends Controller
{
    public function __construct(
        private readonly ShopService $shopService
    ) {}

    /**
     * Display a listing of shops.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Shops/Index', [
            'shops' => Shop::query()
                ->latest()
                ->get()
                ->map(fn (Shop $shop) => [
                    'id' => $shop->id,
                    'uuid' => $shop->uuid,
                    'name' => $shop->name,
                    'slug' => $shop->slug,
                    'business_type' => $shop->business_type?->value ?? $shop->getAttribute('business_type'),
                    'plan' => $shop->plan?->value ?? $shop->getAttribute('plan'),
                    'status' => $shop->status?->value ?? $shop->getAttribute('status'),
                    'created_at' => $shop->created_at?->toDateTimeString(),
                ]),
        ]);
    }

    /**
     * Store a newly created shop (and its owner user).
     */
    public function store(StoreShopRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $ownerPassword = $data['owner_password'] ?? Str::password(length: 14);

        DB::transaction(function () use ($data, $ownerPassword): void {
            $shop = Shop::create([
                'name' => $data['shop_name'],
                'business_type' => $data['business_type'],
                'plan' => $data['plan'],
                'status' => $data['status'],
            ]);

            $owner = User::create([
                'shop_id' => $shop->id,
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => Hash::make((string) $ownerPassword),
                'status' => 1,
            ]);

            app(PermissionRegistrar::class)->setPermissionsTeamId($shop->id);
            $owner->assignRole('shop-owner');

            $scheme = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http';
            $tenantUrl = sprintf('%s://%s.%s/dashboard', $scheme, $shop->slug, (string) config('app.domain', 'localhost'));

            $owner->notify(new PlatformAccessNotification(
                appName: (string) config('app.name', 'Sutra'),
                loginUrl: rtrim((string) config('app.url', 'http://localhost'), '/').'/login',
                tenantUrl: $tenantUrl,
                email: $owner->email,
                password: (string) $ownerPassword,
                roleName: 'shop-owner',
            ));
        });

        return back()->with('success', 'Shop created successfully.');
    }

    /**
     * Update a shop.
     */
    public function update(ShopUpdateRequest $request, Shop $shop): RedirectResponse
    {
        $this->shopService->update($shop, $request->validated());

        return back()->with('success', 'Shop updated successfully.');
    }

    /**
     * Delete a shop (and its shop-scoped data).
     */
    public function destroy(Shop $shop): RedirectResponse
    {
        DB::transaction(function () use ($shop): void {
            User::query()->where('shop_id', $shop->id)->delete();

            $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';
            Role::query()->where($teamsKey, $shop->id)->delete();

            $shop->delete();
        });

        return back()->with('success', 'Shop deleted successfully.');
    }
}
