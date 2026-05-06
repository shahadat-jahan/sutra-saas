<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\DailySummary;
use Illuminate\Pagination\Paginator;

interface DailySummaryRepositoryInterface
{
    /**
     * Get all daily summaries for a shop.
     */
    public function getByShop(string $shopId): Paginator;

    /**
     * Find a daily summary by ID.
     */
    public function find(string $id): ?DailySummary;

    /**
     * Create a new daily summary.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): DailySummary;

    /**
     * Update a daily summary.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(DailySummary $summary, array $data): bool;

    /**
     * Delete a daily summary.
     */
    public function delete(DailySummary $summary): ?bool;
}
