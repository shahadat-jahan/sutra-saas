<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Enums\Plan;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreShopRequest extends FormRequest
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
        return [
            'shop_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', Rule::enum(BusinessType::class)],
            'plan' => ['required', Rule::enum(Plan::class)],
            'status' => ['required', Rule::enum(ActiveStatus::class)],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class.',email',
            ],
            'owner_password' => ['nullable', Password::defaults()],
        ];
    }
}
