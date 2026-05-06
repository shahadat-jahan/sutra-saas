<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Models\Shop;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ShopUpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'business_type' => ['sometimes', Rule::enum(BusinessType::class)],
            'enabled_modules' => ['sometimes', 'array'],
            'enabled_modules.*' => ['string', Rule::in($availableModules)],
            'is_free' => ['sometimes', 'boolean'],
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->has('enabled_modules')) {
                    return;
                }

                $enabledModules = $this->input('enabled_modules', []);

                if (! in_array('pos', $enabledModules, true)) {
                    $validator->errors()->add('enabled_modules', 'POS module is mandatory.');
                }
            },
        ];
    }
}
