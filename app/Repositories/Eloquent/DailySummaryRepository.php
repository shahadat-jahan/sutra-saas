<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\DailySummary;
use App\Repositories\Interfaces\DailySummaryRepositoryInterface;
use Illuminate\Pagination\Paginator;

final class DailySummaryRepository implements DailySummaryRepositoryInterface
{
    private const DEFAULT_PER_PAGE = 15;

    /**
     * {@inheritdoc}
     */
    public function getByShop(string $shopId): Paginator
    {
        return DailySummary::where('shop_id', $shopId)
            ->latest()
            ->simplePaginate(self::DEFAULT_PER_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $id): ?DailySummary
    {
        return DailySummary::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): DailySummary
    {
        return DailySummary::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(DailySummary $summary, array $data): bool
    {
        return $summary->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(DailySummary $summary): ?bool
    {
        return $summary->delete();
    }
}

