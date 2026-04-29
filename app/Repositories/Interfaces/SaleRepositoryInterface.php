<?php

namespace App\Repositories\Interfaces;

use App\Models\Sale;

interface SaleRepositoryInterface
{
    /**
     * Create a new sale record.
     * 
     * @param array $data
     * @return Sale
     */
    public function create(array $data): Sale;

    /**
     * Find a sale by ID.
     * 
     * @param int|string $id
     * @return Sale|null
     */
    public function find(int|string $id): ?Sale;
}
