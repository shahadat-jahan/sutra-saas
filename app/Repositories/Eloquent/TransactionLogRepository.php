<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\TransactionLog;
use App\Repositories\Interfaces\TransactionLogRepositoryInterface;
use Illuminate\Pagination\Paginator;

final class TransactionLogRepository implements TransactionLogRepositoryInterface
{
    private const DEFAULT_PER_PAGE = 15;

    /**
     * {@inheritdoc}
     */
    public function getByShop(string $shopId): Paginator
    {
        return TransactionLog::where('shop_id', $shopId)
            ->latest()
            ->simplePaginate(self::DEFAULT_PER_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $id): ?TransactionLog
    {
        return TransactionLog::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): TransactionLog
    {
        return TransactionLog::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(TransactionLog $log, array $data): bool
    {
        return $log->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TransactionLog $log): ?bool
    {
        return $log->delete();
    }
}

