<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'shop_id' => $data['shop_id'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
