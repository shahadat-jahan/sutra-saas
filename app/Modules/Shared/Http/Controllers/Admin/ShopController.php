<?php

namespace App\Modules\Shared\Http\Controllers\Admin;

use App\Events\ShopCreatedEvent;
use App\Events\ShopUpdatedEvent;
use App\Events\ShopDeletedEvent;
use App\Modules\Shared\Application\Services\ShopService;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Modules\Shared\Http\Controllers\Controller;
use App\Modules\Shared\Http\Requests\Admin\ShopUpdateRequest;
use App\Modules\Shared\Http\Requests\Admin\StoreShopRequest;
use App\Notifications\PlatformAccessNotification;
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
        $moduleCatalog = Shop::moduleCatalog();

        return Inertia::render('Admin/Shops/Index', [
            'module_catalog' => $moduleCatalog,
            'shops' => Shop::query()
                ->latest()
                ->get()
                ->map(fn (Shop $shop) => [
                    'id' => $shop->id,
                    'uuid' => $shop->uuid,
                    'name' => $shop->name,
                    'slug' => $shop->slug,
                    'business_type' => $shop->business_type?->value ?? $shop->getAttribute('business_type'),
                    'enabled_modules' => $shop->enabled_modules ?? [],
                    'is_free' => $shop->is_free,
                    'monthly_price' => $shop->monthlyPrice(),
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
        $ownerPassword = Str::password(14);

        DB::transaction(function () use ($data, $ownerPassword): void {
            $shop = Shop::create([
                'name' => $data['shop_name'],
                'business_type' => $data['business_type'],
                'enabled_modules' => $data['enabled_modules'],
                'is_free' => (bool) ($data['is_free'] ?? false),
                'status' => $data['status'],
            ]);
            $owner = User::create([
                'shop_id' => $shop->id,
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => $ownerPassword,
                'status' => 1,
            ]);

            event(new ShopCreatedEvent($shop, $data, $ownerPassword));
        });

        return back()->with('success', 'Shop created successfully.');
    }

    /**
     * Update a shop.
     */
    public function update(ShopUpdateRequest $request, Shop $shop): RedirectResponse
    {
        $this->shopService->update($shop, $request->validated());
        event(new ShopUpdatedEvent($shop, $request->validated()));
        return back()->with('success', 'Shop updated successfully.');
    }

    /**
     * Delete a shop (and its shop-scoped data).
     */
    public function destroy(Shop $shop): RedirectResponse
    {
        DB::transaction(function () use ($shop): void {
            event(new ShopDeletedEvent($shop));
        });

        return back()->with('success', 'Shop deleted successfully.');
    }
}
