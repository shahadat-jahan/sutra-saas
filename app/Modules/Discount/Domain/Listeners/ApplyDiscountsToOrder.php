<?php

declare(strict_types=1);

namespace App\Modules\Discount\Domain\Listeners;

use App\Enums\DiscountType;
use App\Modules\Discount\Domain\Models\Discount;
use App\Modules\Sales\Domain\Events\OrderTotalCalculating;
use Illuminate\Support\Collection;

/**
 * Listener to apply discounts to an order during total calculation.
 */
final class ApplyDiscountsToOrder
{
    /**
     * Handle the event.
     */
    public function handle(OrderTotalCalculating $event): void
    {
        $order = $event->order;
        $activeDiscounts = Discount::active()->with('discountables')->get();

        $totalDiscount = 0;

        foreach ($activeDiscounts as $discount) {
            // 1. Check if it's a global discount (no specific discountables)
            if ($discount->discountables->isEmpty()) {
                if ($discount->min_order_amount && $order->total_amount < $discount->min_order_amount) {
                    continue;
                }

                $totalDiscount += $this->calculateDiscountValue($discount, $order->total_amount);
            } else {
                // 2. Check if it applies to specific items in the order
                $applicableItems = $this->getApplicableItems($discount, $order->items);

                foreach ($applicableItems as $item) {
                    $itemSubtotal = $item->price * $item->quantity;
                    $totalDiscount += $this->calculateDiscountValue($discount, $itemSubtotal);
                }
            }
        }

        // Update the order object property
        $order->discount_amount = $totalDiscount;
    }

    /**
     * Calculate discount value based on type.
     */
    private function calculateDiscountValue(Discount $discount, float $baseAmount): float
    {
        if ($discount->type === DiscountType::PERCENTAGE) {
            return ($baseAmount * $discount->value) / 100;
        }

        // Fixed amount
        return (float) $discount->value;
    }

    /**
     * Filter order items that match the discount's discountables.
     */
    private function getApplicableItems(Discount $discount, mixed $items): Collection
    {
        $items = collect($items);
        $discountableUuids = $discount->discountables->pluck('discountable_id')->toArray();

        return $items->filter(function ($item) use ($discountableUuids) {
            // Assuming item has a product_id or uuid that matches discountable_id
            $itemId = $item->product_id ?? $item->uuid ?? $item->id;

            return in_array($itemId, $discountableUuids);
        });
    }
}
