<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Enums\BusinessType;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Validator;

class TenantRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $availableModules = array_keys(Shop::moduleCatalog());

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'shop_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', Rule::enum(BusinessType::class)],
            'enabled_modules' => ['required', 'array'],
            'enabled_modules.*' => ['string', Rule::in($availableModules)],
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
