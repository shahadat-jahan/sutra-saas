<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\TransactionLog;
use Illuminate\Pagination\Paginator;

interface TransactionLogRepositoryInterface
{
    /**
     * Get all transaction logs for a shop.
     *
     * @param string $shopId
     * @return Paginator
     */
    public function getByShop(string $shopId): Paginator;

    /**
     * Find a transaction log by ID.
     *
     * @param string $id
     * @return TransactionLog|null
     */
    public function find(string $id): ?TransactionLog;

    /**
     * Create a new transaction log.
     *
     * @param array<string, mixed> $data
     * @return TransactionLog
     */
    public function create(array $data): TransactionLog;

    /**
     * Update a transaction log.
     *
     * @param TransactionLog $log
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(TransactionLog $log, array $data): bool;

    /**
     * Delete a transaction log.
     *
     * @param TransactionLog $log
     * @return bool|null
     */
    public function delete(TransactionLog $log): ?bool;
}

