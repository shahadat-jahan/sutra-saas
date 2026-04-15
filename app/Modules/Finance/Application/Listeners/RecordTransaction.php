<?php

namespace App\Modules\Finance\Application\Listeners;

use App\Models\TransactionLog;
use App\Modules\Pos\Domain\Events\SaleCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Records a credit transaction in the financial ledger
 * when a sale is completed.
 */
class RecordTransaction implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     */
    public int $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(SaleCompleted $event): void
    {
        TransactionLog::create([
            'shop_id' => $event->shopId,
            'user_id' => $event->userId,
            'amount' => $event->totalAmount,
            'type' => 'credit',
            'payment_method' => $event->paymentMethod,
            'reference_id' => $event->referenceId,
            'note' => "POS sale completed. Ref: {$event->referenceId}",
        ]);

        Log::info('RecordTransaction: Sale transaction recorded.', [
            'shop_id' => $event->shopId,
            'amount' => $event->totalAmount,
            'payment_method' => $event->paymentMethod,
            'reference_id' => $event->referenceId,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(SaleCompleted $event, \Throwable $exception): void
    {
        Log::error('RecordTransaction: Failed to record transaction.', [
            'shop_id' => $event->shopId,
            'reference_id' => $event->referenceId,
            'error' => $exception->getMessage(),
        ]);
    }
}
