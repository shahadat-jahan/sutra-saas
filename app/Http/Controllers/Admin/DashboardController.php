<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_shops' => Shop::count(),
                'total_users' => User::count(),
                'active_shops' => Shop::where('status', 1)->count(),
            ]
        ]);
    }
}
