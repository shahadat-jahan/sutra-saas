<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\InventoryLog;
use Illuminate\Pagination\Paginator;

interface InventoryLogRepositoryInterface
{
    /**
     * Get all inventory logs for a shop.
     *
     * @param string $shopId
     * @return Paginator
     */
    public function getByShop(string $shopId): Paginator;

    /**
     * Find an inventory log by ID.
     *
     * @param string $id
     * @return InventoryLog|null
     */
    public function find(string $id): ?InventoryLog;

    /**
     * Create a new inventory log.
     *
     * @param array<string, mixed> $data
     * @return InventoryLog
     */
    public function create(array $data): InventoryLog;

    /**
     * Update an inventory log.
     *
     * @param InventoryLog $log
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(InventoryLog $log, array $data): bool;

    /**
     * Delete an inventory log.
     *
     * @param InventoryLog $log
     * @return bool|null
     */
    public function delete(InventoryLog $log): ?bool;
}

