<?php

declare(strict_types=1);

namespace App\Modules\Discount\Domain\Models;

use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Discountable Model
 *
 * Polymorphic relation to link discounts to products, categories, etc.
 */
final class Discountable extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'uuid',
        'shop_id',
        'discount_id',
        'discountable_id',
        'discountable_type',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Get the parent discountable model (Product, etc.).
     */
    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }
}
