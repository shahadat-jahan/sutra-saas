<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\InventoryLog;
use Illuminate\Pagination\Paginator;

interface InventoryLogRepositoryInterface
{
    /**
     * Get all inventory logs for a shop.
     */
    public function getByShop(string $shopId): Paginator;

    /**
     * Find an inventory log by ID.
     */
    public function find(string $id): ?InventoryLog;

    /**
     * Create a new inventory log.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): InventoryLog;

    /**
     * Update an inventory log.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(InventoryLog $log, array $data): bool;

    /**
     * Delete an inventory log.
     */
    public function delete(InventoryLog $log): ?bool;
}
