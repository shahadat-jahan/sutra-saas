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
     * @param  array<string, mixed>  $data
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
     */
    public function deleteAccount(User $user): void
    {
        Auth::logout();

        $this->userRepository->delete($user);
    }
}
