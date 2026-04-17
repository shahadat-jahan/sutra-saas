<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Shops/Index', [
            'shops' => Shop::latest()->get()
        ]);
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'status' => 'required|integer',
        ]);

        $shop->update($validated);

        return back()->with('success', 'Shop updated successfully');
    }
}
