<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Infrastructure\Repositories\Eloquent;

use App\Modules\Dealer\Domain\Models\DealerLedger;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerLedgerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class DealerLedgerRepository implements DealerLedgerRepositoryInterface
{
    public function getByDealer(string $dealerId): Collection
    {
        return DealerLedger::where('dealer_id', $dealerId)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create(array $data): DealerLedger
    {
        return DealerLedger::create($data);
    }

    public function getLatestEntry(string $dealerId): ?DealerLedger
    {
        return DealerLedger::where('dealer_id', $dealerId)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function idempotencyKeyExists(string $key): bool
    {
        return DealerLedger::where('idempotency_key', $key)->exists();
    }
}
