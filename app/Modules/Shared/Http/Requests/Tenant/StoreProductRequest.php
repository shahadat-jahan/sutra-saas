<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Requests\Tenant;

use App\Support\TenantManager;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        $tenantManager = app(TenantManager::class);
        $isPharmaEnabled = $tenantManager->isModuleEnabled('pharma');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'attributes' => ['nullable', 'array'],
        ];

        if ($isPharmaEnabled) {
            $rules['attributes.generic_name'] = ['nullable', 'string', 'max:255'];
        } else {
            // If pharma not enabled, ensure no pharma attributes are sent
            $rules['attributes.*'] = ['prohibited'];
        }

        return $rules;
    }
}
