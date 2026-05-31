<?php

declare(strict_types=1);

namespace App\Modules\Discount\Application\Services;

use App\Modules\Discount\Infrastructure\Repositories\Interfaces\DiscountRuleRepositoryInterface;
use App\Modules\Sales\Domain\Models\Sale;

final class SlabDiscountService
{
    public function __construct(
        private readonly DiscountRuleRepositoryInterface $discountRuleRepository
    ) {}

    /**
     * Calculate the applicable discount percentage based on the invoice total and dealer tier.
     * We pick the highest discount that applies.
     */
    public function calculateDiscountForSale(Sale $sale): float
    {
        // For a B2B scenario, get the dealer tier from the customer.
        // Assuming Sale has customer which has tier, or we can just fetch rules globally if customer is missing.
        $dealerTier = $sale->customer?->tier; // If customer is a dealer in a merged model, or simply dealer.

        // Since the user might be a dealer, let's fetch rules
        $rules = $this->discountRuleRepository->getActiveRules((string) $sale->shop_id, $dealerTier?->value);

        $totalAmount = (float) $sale->total_amount;
        $highestDiscount = 0.0;

        foreach ($rules as $rule) {
            // Check if amount is within slab
            if ($totalAmount >= (float) $rule->min_amount &&
                ($rule->max_amount === null || $totalAmount <= (float) $rule->max_amount)) {

                // Keep the highest applicable discount
                if ((float) $rule->discount_percentage > $highestDiscount) {
                    $highestDiscount = (float) $rule->discount_percentage;
                }
            }
        }

        return $highestDiscount;
    }
}
