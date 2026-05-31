<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDealerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorizations typically handled by Policies in this app
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'trade_license' => ['nullable', 'string', 'max:255'],
            'tin' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['numeric', 'min:0'],
            'payment_terms' => ['string', 'max:50'],
            'tier' => ['integer', 'in:1,2,3'],
            'territory' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'commission_plan_id' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'array'],
            'status' => ['integer', 'in:1,2,3'],
        ];
    }
}
