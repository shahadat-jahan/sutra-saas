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
            'users' => $this->userService->getAllUsers()->map(fn ($user) => [
                'id' => $user->id,
                'uuid' => $user->uuid ?? null,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toDateTimeString(),
                'shop' => $user->shop ? [
                    'id' => $user->shop->id,
                    'uuid' => $user->shop->uuid,
                    'name' => $user->shop->name,
                    'slug' => $user->shop->slug,
                ] : null,
            ]),
        ]);
    }
}
