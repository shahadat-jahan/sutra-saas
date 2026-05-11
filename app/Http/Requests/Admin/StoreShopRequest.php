<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
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
        $availableModules = array_keys(Shop::moduleCatalog());

        return [
            'shop_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', Rule::enum(BusinessType::class)],
            'enabled_modules' => ['required', 'array'],
            'enabled_modules.*' => ['string', Rule::in($availableModules)],
            'is_free' => ['nullable', 'boolean'],
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

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $enabledModules = $this->input('enabled_modules', []);

                if (! in_array('inventory', $enabledModules, true)) {
                    $validator->errors()->add('enabled_modules', 'Inventory module is mandatory.');
                }
            },
        ];
    }
}
