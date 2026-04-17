<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\ShopRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class TenantRegistrationService
{
    public function __construct(
        private readonly ShopRepositoryInterface $shopRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Register a new tenant along with the owner user.
     *
     * @param array<string, mixed> $shopData
     * @param array<string, mixed> $userData
     * @return User
     */

    public function registerTenant(array $shopData, array $userData): User
    {
        $user = DB::transaction(function () use ($shopData, $userData) {
            $shop = $this->shopRepository->create($shopData);
            
            $userData['shop_id'] = $shop->id;
            $user = $this->userRepository->create($userData);
            
            // Set Team Context for Spatie Permissions
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($shop->id);
            
            // Assign Shop Owner Role for this specific shop
            $user->assignRole('shop-owner');
            
            return $user;
        });

        event(new \Illuminate\Auth\Events\Registered($user));
        \Illuminate\Support\Facades\Auth::login($user);

        return $user;
    }
}
