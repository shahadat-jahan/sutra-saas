<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\DailySummary;
use Illuminate\Pagination\Paginator;

interface DailySummaryRepositoryInterface
{
    /**
     * Get all daily summaries for a shop.
     *
     * @param string $shopId
     * @return Paginator
     */
    public function getByShop(string $shopId): Paginator;

    /**
     * Find a daily summary by ID.
     *
     * @param string $id
     * @return DailySummary|null
     */
    public function find(string $id): ?DailySummary;

    /**
     * Create a new daily summary.
     *
     * @param array<string, mixed> $data
     * @return DailySummary
     */
    public function create(array $data): DailySummary;

    /**
     * Update a daily summary.
     *
     * @param DailySummary $summary
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(DailySummary $summary, array $data): bool;

    /**
     * Delete a daily summary.
     *
     * @param DailySummary $summary
     * @return bool|null
     */
    public function delete(DailySummary $summary): ?bool;
}

