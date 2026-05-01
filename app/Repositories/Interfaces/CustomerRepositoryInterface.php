<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    /**
     * Get all active customers for the current shop.
     * 
     * @return Collection
     */
    public function getActiveCustomers(): Collection;

    /**
     * Find a customer by ID.
     * 
     * @param int|string $id
     * @return Customer|null
     */
    public function find(int|string $id): ?Customer;

    /**
     * Create a new customer.
     * 
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer;

    /**
     * Update a customer's balance.
     * 
     * @param Customer $customer
     * @param float $amount
     * @return void
     */
    public function incrementBalance(Customer $customer, float $amount): void;
}
