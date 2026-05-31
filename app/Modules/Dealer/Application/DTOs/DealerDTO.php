<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Application\DTOs;

/**
 * Data Transfer Object for Dealer creation and updates.
 *
 * Immutable value object — all properties are readonly.
 * Constructed from validated FormRequest data via the static factory method.
 */
final readonly class DealerDTO
{
    public function __construct(
        public string $businessName,
        public ?string $contactPerson = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $address = null,
        public ?string $tradeLicense = null,
        public ?string $tin = null,
        public float $creditLimit = 0,
        public string $paymentTerms = 'net-30',
        public int $tier = 1,
        public ?string $territory = null,
        public ?int $userId = null,
        public ?int $commissionPlanId = null,
        public ?array $metadata = null,
        public int $status = 1,
    ) {}

    /**
     * Create a DTO from validated request data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            businessName: $data['business_name'],
            contactPerson: $data['contact_person'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            address: $data['address'] ?? null,
            tradeLicense: $data['trade_license'] ?? null,
            tin: $data['tin'] ?? null,
            creditLimit: (float) ($data['credit_limit'] ?? 0),
            paymentTerms: $data['payment_terms'] ?? 'net-30',
            tier: (int) ($data['tier'] ?? 1),
            territory: $data['territory'] ?? null,
            userId: isset($data['user_id']) ? (int) $data['user_id'] : null,
            commissionPlanId: isset($data['commission_plan_id']) ? (int) $data['commission_plan_id'] : null,
            metadata: $data['metadata'] ?? null,
            status: (int) ($data['status'] ?? 1),
        );
    }

    /**
     * Convert to array for Eloquent create/update operations.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'business_name' => $this->businessName,
            'contact_person' => $this->contactPerson,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'trade_license' => $this->tradeLicense,
            'tin' => $this->tin,
            'credit_limit' => $this->creditLimit,
            'payment_terms' => $this->paymentTerms,
            'tier' => $this->tier,
            'territory' => $this->territory,
            'user_id' => $this->userId,
            'commission_plan_id' => $this->commissionPlanId,
            'metadata' => $this->metadata,
            'status' => $this->status,
        ];
    }
}
