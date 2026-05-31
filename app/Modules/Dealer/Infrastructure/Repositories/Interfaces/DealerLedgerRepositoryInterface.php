<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Infrastructure\Repositories\Interfaces;

use App\Modules\Dealer\Domain\Models\DealerLedger;
use Illuminate\Database\Eloquent\Collection;

interface DealerLedgerRepositoryInterface
{
    /**
     * Get all ledger entries for a dealer.
     */
    public function getByDealer(string $dealerId): Collection;

    /**
     * Create a new ledger entry.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): DealerLedger;

    /**
     * Get the latest ledger entry for a dealer (for running balance).
     */
    public function getLatestEntry(string $dealerId): ?DealerLedger;

    /**
     * Check if an idempotency key already exists.
     */
    public function idempotencyKeyExists(string $key): bool;
}
