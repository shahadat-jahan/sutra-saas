<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\InventoryLog;
use App\Repositories\Interfaces\InventoryLogRepositoryInterface;
use Illuminate\Pagination\Paginator;

final class InventoryLogRepository implements InventoryLogRepositoryInterface
{
    private const DEFAULT_PER_PAGE = 15;

    /**
     * {@inheritdoc}
     */
    public function getByShop(string $shopId): Paginator
    {
        return InventoryLog::where('shop_id', $shopId)
            ->latest()
            ->simplePaginate(self::DEFAULT_PER_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $id): ?InventoryLog
    {
        return InventoryLog::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): InventoryLog
    {
        return InventoryLog::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(InventoryLog $log, array $data): bool
    {
        return $log->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(InventoryLog $log): ?bool
    {
        return $log->delete();
    }
}

