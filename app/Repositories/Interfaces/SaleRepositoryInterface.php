<?php

namespace App\Repositories\Interfaces;

use App\Models\Sale;

interface SaleRepositoryInterface
{
    /**
     * Create a new sale record.
     */
    public function create(array $data): Sale;

    /**
     * Find a sale by ID.
     */
    public function find(int|string $id): ?Sale;
}
