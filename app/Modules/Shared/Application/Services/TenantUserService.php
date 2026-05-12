<?php

declare(strict_types=1);

namespace App\Modules\Shared\Application\Services;

use App\Modules\Shared\Domain\Models\User;
use App\Notifications\PlatformAccessNotification;
use App\Modules\Shared\Infrastructure\Repositories\Interfaces\UserRepositoryInterface;
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
     * @param  array<string, mixed>  $data
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

        $user->notify(new PlatformAccessNotification(
            appName: (string) config('app.name', 'Sutra'),
            loginUrl: rtrim((string) config('app.url', 'http://localhost'), '/').'/login',
            tenantUrl: $user->shop ? sprintf('%s://%s.%s/dashboard',
                parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http',
                $user->shop->slug,
                (string) config('app.domain', 'localhost'),
            ) : null,
            email: $user->email,
            password: (string) $data['password'],
            roleName: (string) $data['role'],
        ));

        return $user;
    }

    /**
     * Update a user within the current shop (including role assignment).
     *
     * @param  array<string,mixed>  $data
     */
    public function updateUser(User $user, string $shopId, array $data): bool
    {
        if ($user->shop_id !== $shopId) {
            abort(403);
        }

        $payload = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make((string) $data['password']);
        }

        $updated = $this->userRepository->update($user, $payload);

        if (! empty($data['role'])) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($shopId);
            $user->syncRoles([(string) $data['role']]);
        }

        return $updated;
    }

    /**
     * Delete a user after authorization check.
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
