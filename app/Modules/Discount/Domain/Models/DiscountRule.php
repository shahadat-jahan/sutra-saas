<?php

declare(strict_types=1);

namespace App\Modules\Discount\Domain\Models;

use App\Modules\Shared\Domain\Models\Shop;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DiscountRule Model
 *
 * Part of the Discount Module. Represents a slab tier for discounting.
 */
final class DiscountRule extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'product_id',
        'category',
        'dealer_tier',
        'min_amount',
        'max_amount',
        'discount_percentage',
        'is_active',
        'valid_from',
        'valid_until',
        'priority',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'is_active' => 'boolean',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
            'priority' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // Product relation would go here if we were strongly coupling it, but it's optional.
}
