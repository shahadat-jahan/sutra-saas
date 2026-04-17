<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

final class TenantUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get all users for a shop.
     *
     * @param string $shopId
     * @return Collection
     */
    public function getUsersByShop(string $shopId): Collection
    {
        return User::where('shop_id', $shopId)
            ->with('roles')
            ->latest()
            ->get();
    }

    /**
     * Create a new user for a shop.
     *
     * @param string $shopId
     * @param array<string, mixed> $data
     * @return User
     */
    public function createUser(string $shopId, array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'shop_id' => $shopId,
        ];

        $user = $this->userRepository->create($userData);

        // Set Team Context for Spatie Permissions
        app(PermissionRegistrar::class)->setPermissionsTeamId($shopId);

        // Assign the specified role
        $user->assignRole($data['role']);

        return $user;
    }

    /**
     * Delete a user after authorization check.
     *
     * @param User $user
     * @param string $shopId
     * @return bool|null
     */
    public function deleteUser(User $user, string $shopId): ?bool
    {
        // Ensure user belongs to the same shop
        if ($user->shop_id !== $shopId) {
            abort(403);
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return false;
        }

        return $this->userRepository->delete($user);
    }
}
