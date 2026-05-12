<?php

namespace App\Modules\Sales\Infrastructure\Repositories\Interfaces;

use App\Modules\Sales\Domain\Models\Sale;

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
