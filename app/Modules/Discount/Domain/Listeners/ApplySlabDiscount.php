<?php

declare(strict_types=1);

namespace App\Modules\Discount\Domain\Listeners;

use App\Modules\Discount\Application\Services\SlabDiscountService;
use App\Modules\Sales\Domain\Events\SaleCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener that applies slab-based discounts to an order when it's created.
 */
class ApplySlabDiscount implements ShouldQueue
{
    public function __construct(
        private readonly SlabDiscountService $slabDiscountService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SaleCreated $event): void
    {
        $sale = $event->sale;

        // Calculate the highest applicable discount percentage based on total_amount
        $discountPercentage = $this->slabDiscountService->calculateDiscountForSale($sale);

        if ($discountPercentage > 0) {
            $discountAmount = ($sale->total_amount * $discountPercentage) / 100;

            // Adjust the total and due amounts
            $sale->total_amount -= $discountAmount;
            $sale->due_amount -= $discountAmount;

            // Log the discount in metadata for auditability
            $metadata = $sale->metadata ?? [];
            $metadata['slab_discount_applied'] = [
                'percentage' => $discountPercentage,
                'amount' => $discountAmount,
                'original_total' => $sale->total_amount + $discountAmount,
            ];
            $sale->metadata = $metadata;

            // Save without triggering events again to avoid infinite loops,
            // or use saveQuietly() if using eloquent events heavily.
            $sale->saveQuietly();
        }
    }
}
