<?php

declare(strict_types=1);

namespace App\Modules\Discount\Infrastructure\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface DiscountRuleRepositoryInterface
{
    /**
     * Get active discount rules for a specific tenant and optional dealer tier.
     */
    public function getActiveRules(string $shopId, ?int $dealerTier = null): Collection;
}
