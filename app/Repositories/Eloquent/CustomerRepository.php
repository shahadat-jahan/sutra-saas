<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Get all active customers for the current shop.
     * Selects only required columns for POS optimization.
     */
    public function getActiveCustomers(): Collection
    {
        return Customer::active()
            ->select(['id', 'name', 'phone', 'current_balance'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function find(int|string $id): ?Customer
    {
        return Customer::find($id);
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function incrementBalance(Customer $customer, float $amount): void
    {
        $customer->increment('current_balance', $amount);
    }
}
