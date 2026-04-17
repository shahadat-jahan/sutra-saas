<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class AdminUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get all users with their shop information.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return User::with('shop')->latest()->get();
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool|null
     */
    public function deleteUser(User $user): ?bool
    {
        return $this->userRepository->delete($user);
    }
}

