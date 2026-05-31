<?php

declare(strict_types=1);

namespace App\Modules\Shared\Application\Services;

use App\Modules\Shared\Domain\Models\User;
use App\Modules\Shared\Infrastructure\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class AdminUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get all users with their shop information.
     */
    public function getAllUsers(): Collection
    {
        return User::with('shop')->latest()->get();
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): ?bool
    {
        return $this->userRepository->delete($user);
    }
}
