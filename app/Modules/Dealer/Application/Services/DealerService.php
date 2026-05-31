<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Application\Services;

use App\Modules\Dealer\Application\DTOs\DealerDTO;
use App\Modules\Dealer\Domain\Enums\LedgerEntryType;
use App\Modules\Dealer\Domain\Models\Dealer;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerLedgerRepositoryInterface;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

final class DealerService
{
    public function __construct(
        private readonly DealerRepositoryInterface $dealerRepository,
        private readonly DealerLedgerRepositoryInterface $dealerLedgerRepository
    ) {}

    /**
     * Create a new dealer from DTO.
     */
    public function createDealer(DealerDTO $dto): Dealer
    {
        return $this->dealerRepository->create($dto->toArray());
    }

    /**
     * Update an existing dealer from DTO.
     */
    public function updateDealer(Dealer $dealer, DealerDTO $dto): bool
    {
        return $this->dealerRepository->update($dealer, $dto->toArray());
    }

    /**
     * Check if a dealer has sufficient available credit for a transaction.
     */
    public function checkCreditLimit(Dealer $dealer, float $amount): bool
    {
        return $dealer->canAfford($amount);
    }

    /**
     * Record a debit or credit against a dealer's ledger and update their outstanding balance.
     * Uses a transaction to ensure atomic operations.
     *
     * @param  LedgerEntryType  $type  Debit (1) increases balance, Credit (2) decreases it.
     *
     * @throws Exception
     */
    public function recordLedgerEntry(
        Dealer $dealer,
        LedgerEntryType $type,
        float $amount,
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?string $narration = null,
        ?string $idempotencyKey = null,
        ?array $metadata = null
    ): bool {
        if ($amount <= 0) {
            throw new Exception('Amount must be greater than zero.');
        }

        // Idempotency check
        if ($idempotencyKey && $this->dealerLedgerRepository->idempotencyKeyExists($idempotencyKey)) {
            // Already processed
            return true;
        }

        DB::beginTransaction();

        try {
            // Amount to change the balance by (Debit is positive, Credit is negative)
            $balanceChange = $type === LedgerEntryType::DEBIT ? $amount : -$amount;

            // Update balance atomically
            if (! $this->dealerRepository->updateOutstandingBalance($dealer, $balanceChange)) {
                throw new Exception('Failed to update dealer balance.');
            }

            // The new running balance is now on the $dealer model because updateOutstandingBalance refreshed it
            $runningBalance = $dealer->outstanding_balance;

            // Create ledger entry
            $this->dealerLedgerRepository->create([
                'shop_id' => $dealer->shop_id,
                'dealer_id' => $dealer->id,
                'user_id' => auth()->id(), // May be null in background jobs
                'type' => $type,
                'amount' => $amount,
                'running_balance' => $runningBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'idempotency_key' => $idempotencyKey,
                'narration' => $narration,
                'metadata' => $metadata,
                'transaction_date' => now(),
            ]);

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
