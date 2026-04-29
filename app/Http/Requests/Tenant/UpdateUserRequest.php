<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');
        $shopId = (string) $this->user()->shop_id;
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($user->id),
            ],
            'password' => ['nullable', Password::defaults()],
            'role' => [
                'required',
                'string',
                Rule::exists(Role::class, 'name')->where(fn ($q) => $q->whereNull($teamsKey)->orWhere($teamsKey, $shopId)),
            ],
        ];
    }
}

