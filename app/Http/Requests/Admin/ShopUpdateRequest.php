<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Enums\Plan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'business_type' => ['sometimes', Rule::enum(BusinessType::class)],
            'plan' => ['sometimes', Rule::enum(Plan::class)],
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }
}
