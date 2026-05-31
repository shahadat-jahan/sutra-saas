<?php

declare(strict_types=1);

namespace App\Modules\Discount\Infrastructure\Repositories\Eloquent;

use App\Modules\Discount\Domain\Models\DiscountRule;
use App\Modules\Discount\Infrastructure\Repositories\Interfaces\DiscountRuleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class DiscountRuleRepository implements DiscountRuleRepositoryInterface
{
    public function getActiveRules(string $shopId, ?int $dealerTier = null): Collection
    {
        $query = DiscountRule::where('shop_id', $shopId)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            });

        if ($dealerTier !== null) {
            $query->where(function ($q) use ($dealerTier) {
                $q->whereNull('dealer_tier')->orWhere('dealer_tier', $dealerTier);
            });
        }

        // Order by priority (lowest first) then min_amount descending to evaluate largest slab first
        return $query->orderBy('priority', 'asc')
            ->orderBy('min_amount', 'desc')
            ->get();
    }
}
