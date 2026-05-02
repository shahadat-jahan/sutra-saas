<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Shop;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateModulePricingRequest extends FormRequest
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
        $catalog = Shop::moduleCatalog();
        $rules = [
            'modules' => ['required', 'array'],
        ];

        foreach (array_keys($catalog) as $moduleKey) {
            $rules["modules.$moduleKey.monthly_price"] = ['required', 'integer', 'min:0'];
        }

        return $rules;
    }
}
