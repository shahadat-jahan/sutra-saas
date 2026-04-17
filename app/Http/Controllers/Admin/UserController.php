<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminUserService;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly AdminUserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => $this->userService->getAllUsers(),
        ]);
    }
}
