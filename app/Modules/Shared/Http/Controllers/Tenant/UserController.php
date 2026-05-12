<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Tenant;

use App\Modules\Shared\Http\Controllers\Controller;
use App\Modules\Shared\Http\Requests\Tenant\StoreUserRequest;
use App\Modules\Shared\Http\Requests\Tenant\UpdateUserRequest;
use App\Modules\Shared\Domain\Models\User;
use App\Modules\Shared\Application\Services\TenantUserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

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
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        return Inertia::render('Tenant/Users/Index', [
            'users' => $this->userService->getUsersByShop($shop->id),
            'roles' => Role::query()
                ->where(fn ($q) => $q->whereNull($teamsKey)->orWhere($teamsKey, $shop->id))
                ->orderBy('name')
                ->get(['id', 'name']),
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

        if (! $this->userService->deleteUser($user, $shopId)) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Update a user.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $shop = auth()->user()->shop;

        $this->userService->updateUser($user, (string) $shop->id, $request->validated());

        return back()->with('success', 'User updated successfully.');
    }
}
