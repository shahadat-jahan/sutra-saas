<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Update user profile information.
     *
     * @param User $user
     * @param array<string, mixed> $data
     * @return bool
     */
    public function updateProfile(User $user, array $data): bool
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return $this->userRepository->update($user, $user->getAttributes());
    }

    /**
     * Delete user account entirely and logout.
     *
     * @param User $user
     * @return void
     */
    public function deleteAccount(User $user): void
    {
        Auth::logout();
        
        $this->userRepository->delete($user);
    }
}
