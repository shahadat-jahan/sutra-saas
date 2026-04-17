<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShopUpdateRequest;
use App\Models\Shop;
use App\Services\ShopService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

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
            'shops' => Shop::latest()->get(),
        ]);
    }

    /**
     * Update a shop.
     */
    public function update(ShopUpdateRequest $request, Shop $shop): RedirectResponse
    {
        $this->shopService->update($shop, $request->validated());

        return back()->with('success', 'Shop updated successfully.');
    }
}
