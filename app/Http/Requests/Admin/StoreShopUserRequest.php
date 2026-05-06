<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class StoreShopUserRequest extends FormRequest
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
        $shop = $this->route('shop');
        $shopId = is_object($shop) ? (string) $shop->id : (string) $shop;
        $teamsKey = app(PermissionRegistrar::class)->teamsKey ?? 'team_id';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['nullable', Password::defaults()],
            'role' => [
                'required',
                'string',
                Rule::exists(Role::class, 'name')->where(fn ($q) => $q->whereNull($teamsKey)->orWhere($teamsKey, $shopId)),
            ],
        ];
    }
}
