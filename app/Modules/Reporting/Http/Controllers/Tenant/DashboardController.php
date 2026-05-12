<?php

declare(strict_types=1);

namespace App\Modules\Reporting\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Modules\Reporting\Application\Services\DashboardService;
use App\Support\TenantManager;
use Inertia\Inertia;
use Inertia\Response;

/**
 * DashboardController
 * 
 * Part of the Reporting Module.
 */
final class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly TenantManager $tenantManager
    ) {}

    public function index(): Response
    {
        $tenant = $this->tenantManager->getTenant();
        
        return Inertia::render('Dashboard', [
            'dashboardData' => $this->dashboardService->getTenantDashboardData($tenant->id),
            'shopName' => $tenant->name,
        ]);
    }
}
