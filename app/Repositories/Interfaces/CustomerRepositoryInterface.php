<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    /**
     * Get all active customers for the current shop.
     */
    public function getActiveCustomers(): Collection;

    /**
     * Find a customer by ID.
     */
    public function find(int|string $id): ?Customer;

    /**
     * Create a new customer.
     */
    public function create(array $data): Customer;

    /**
     * Update a customer's balance.
     */
    public function incrementBalance(Customer $customer, float $amount): void;
}
