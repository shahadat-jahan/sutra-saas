<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Admin;

use App\Modules\Reporting\Application\Services\DashboardService;
use App\Modules\Shared\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    /**
     * Display the admin dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            ...$this->dashboardService->getAdminDashboardData(),
        ]);
    }
}
