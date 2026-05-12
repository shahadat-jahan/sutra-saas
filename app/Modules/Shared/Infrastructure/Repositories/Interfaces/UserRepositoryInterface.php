<?php

declare(strict_types=1);

namespace App\Modules\Shared\Infrastructure\Repositories\Interfaces;

use App\Modules\Shared\Domain\Models\User;

interface UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User;

    /**
     * Update an existing user.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(User $user, array $data): bool;

    /**
     * Delete a user.
     */
    public function delete(User $user): ?bool;
}
