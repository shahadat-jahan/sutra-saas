<?php

namespace App\Modules\Pos\Application\Services;

use App\Enums\ActiveStatus;
use App\Modules\Sales\Domain\Models\Customer;
use App\Modules\Sales\Infrastructure\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    /**
     * Find an existing customer by ID or create a new one for the current shop.
     */
    public function findOrCreate(array $data): Customer
    {
        // If ID is provided, fetch existing
        if (! empty($data['id'])) {
            return $this->customerRepository->find($data['id']);
        }

        // Otherwise create a new record (Requirement: Baki Logic)
        return $this->customerRepository->create([
            'shop_id' => $data['shop_id'] ?? Auth::user()->shop_id,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'credit_limit' => $data['credit_limit'] ?? 0,
            'current_balance' => 0,
            'status' => ActiveStatus::ACTIVE,
        ]);
    }
}
