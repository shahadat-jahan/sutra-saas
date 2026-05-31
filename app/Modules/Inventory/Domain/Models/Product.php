<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Domain\Models;

use App\Enums\ActiveStatus;
use App\Modules\Shared\Domain\Models\Shop;
use App\Traits\HasDynamicAttributes;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product Model
 *
 * Part of the Inventory Module.
 */
final class Product extends Model
{
    use HasDynamicAttributes, HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'name',
        'sku',
        'purchase_price',
        'sale_price',
        'stock_quantity',
        'metadata',
        'attributes',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'decimal:3',
            'metadata' => 'array',
            'attributes' => 'array',
            'status' => ActiveStatus::class
        ];
    }

    protected function getDynamicAttributesColumn(): string
    {
        return 'attributes';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }
}
