<?php

namespace App\Modules\Inventory\Application\Listeners;

use App\Models\InventoryLog;
use App\Models\Product;
use App\Modules\Pos\Domain\Events\SaleCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Decrements product stock and records inventory movement
 * when a sale is completed.
 */
class UpdateInventory implements ShouldQueue
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
        foreach ($event->items as $item) {
            DB::transaction(function () use ($event, $item) {
                // Record inventory outflow log
                InventoryLog::create([
                    'shop_id' => $event->shopId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => 'out',
                    'note' => "Sale ref: {$event->referenceId}",
                ]);

                // Atomically decrement the product stock
                Product::where('id', $item['product_id'])
                    ->where('shop_id', $event->shopId)
                    ->decrement('stock_quantity', $item['quantity']);
            });
        }

        Log::info('UpdateInventory: Stock decremented for sale.', [
            'shop_id' => $event->shopId,
            'reference_id' => $event->referenceId,
            'items_count' => count($event->items),
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(SaleCompleted $event, \Throwable $exception): void
    {
        Log::error('UpdateInventory: Failed to update stock.', [
            'shop_id' => $event->shopId,
            'reference_id' => $event->referenceId,
            'error' => $exception->getMessage(),
        ]);
    }
}
