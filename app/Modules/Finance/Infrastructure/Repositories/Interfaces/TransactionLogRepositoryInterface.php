<?php

declare(strict_types=1);

namespace App\Modules\Finance\Infrastructure\Repositories\Interfaces;

use App\Modules\Finance\Domain\Models\TransactionLog;
use Illuminate\Pagination\Paginator;

interface TransactionLogRepositoryInterface
{
    /**
     * Get all transaction logs for a shop.
     */
    public function getByShop(string $shopId): Paginator;

    /**
     * Find a transaction log by ID.
     */
    public function find(string $id): ?TransactionLog;

    /**
     * Create a new transaction log.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): TransactionLog;

    /**
     * Update a transaction log.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(TransactionLog $log, array $data): bool;

    /**
     * Delete a transaction log.
     */
    public function delete(TransactionLog $log): ?bool;
}
