<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Http\Requests\Admin\UpdateModulePricingRequest;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\SystemSetting;
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
            $newBdt = (int) ($payload[$moduleKey]['monthly_price_bdt'] ?? $moduleConfig['monthly_price_bdt']);
            $newUsd = (int) ($payload[$moduleKey]['monthly_price_usd'] ?? $moduleConfig['monthly_price_usd']);

            // Create log if price changed
            if ($newBdt !== (int) $moduleConfig['monthly_price_bdt'] || $newUsd !== (int) $moduleConfig['monthly_price_usd']) {
                \App\Modules\Shared\Domain\Models\ModulePriceLog::create([
                    'module_key' => $moduleKey,
                    'user_id' => auth()->id(),
                    'old_price_bdt' => $moduleConfig['monthly_price_bdt'],
                    'new_price_bdt' => $newBdt,
                    'old_price_usd' => $moduleConfig['monthly_price_usd'],
                    'new_price_usd' => $newUsd,
                ]);
            }

            $override[$moduleKey] = [
                'monthly_price_bdt' => $newBdt,
                'monthly_price_usd' => $newUsd,
            ];
        }

        SystemSetting::putArray('module_catalog', $override);

        return back()->with('success', 'Module pricing updated successfully.');
    }

    public function moduleLogs(string $moduleKey): Response
    {
        $catalog = Shop::moduleCatalog();
        $module = $catalog[$moduleKey] ?? ['name' => ucfirst($moduleKey)];

        return Inertia::render('Admin/Settings/ModuleLogs', [
            'module_key' => $moduleKey,
            'module_name' => $module['name'],
            'logs' => \App\Modules\Shared\Domain\Models\ModulePriceLog::with('user')
                ->where('module_key', $moduleKey)
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }
}
