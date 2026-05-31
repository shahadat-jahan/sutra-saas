<?php

declare(strict_types=1);

namespace App\Modules\Discount\Domain\Models;

use App\Enums\DiscountType;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Discount Model
 *
 * Represents a discount that can be applied to orders or specific items.
 */
final class Discount extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'uuid',
        'shop_id',
        'name',
        'type',
        'value',
        'min_order_amount',
        'is_active',
        'start_date',
        'end_date',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'type' => DiscountType::class,
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'is_active' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function discountables(): HasMany
    {
        return $this->hasMany(Discountable::class);
    }

    /**
     * Scope to only include active discounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}
