<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Admin;

use App\Modules\Shared\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class PlanController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Plans/Index', [
            'plans' => Plan::all(),
        ]);
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'price_bdt' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $plan->update($validated);

        return back()->with('success', 'Plan pricing updated successfully.');
    }

    public function logs(Plan $plan): Response
    {
        return Inertia::render('Admin/Plans/Logs', [
            'plan' => $plan,
            'logs' => $plan->priceLogs()
                ->with('user:id,name')
                ->latest()
                ->get(),
        ]);
    }
}
