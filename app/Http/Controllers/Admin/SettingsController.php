<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateModulePricingRequest;
use App\Models\Shop;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Settings/Index', [
            'module_catalog' => Shop::moduleCatalog(),
        ]);
    }

    public function updateModulePricing(UpdateModulePricingRequest $request): RedirectResponse
    {
        $catalog = Shop::moduleCatalog();
        $payload = $request->validated('modules');
        $override = [];

        foreach ($catalog as $moduleKey => $moduleConfig) {
            $override[$moduleKey] = [
                'monthly_price_bdt' => (int) ($payload[$moduleKey]['monthly_price_bdt'] ?? $moduleConfig['monthly_price_bdt']),
                'monthly_price_usd' => (int) ($payload[$moduleKey]['monthly_price_usd'] ?? $moduleConfig['monthly_price_usd']),
            ];
        }

        SystemSetting::putArray('module_catalog', $override);

        return back()->with('success', 'Module pricing updated successfully.');
    }
}
