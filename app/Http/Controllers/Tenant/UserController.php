<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreUserRequest;
use App\Models\User;
use App\Services\TenantUserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly TenantUserService $userService
    ) {}

    /**
     * Display a listing of users for the shop.
     */
    public function index(): Response
    {
        $shop = auth()->user()->shop;

        return Inertia::render('Tenant/Users/Index', [
            'users' => $this->userService->getUsersByShop($shop->id),
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $shop = auth()->user()->shop;

        $this->userService->createUser($shop->id, $request->validated());

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $shop = auth()->user()->shop;
        $shopId = $shop->id;

        if (!$this->userService->deleteUser($user, $shopId)) {
            return back()->with('error', 'You cannot delete yourself.');
        }


        return back()->with('success', 'User deleted successfully.');
    }
}
