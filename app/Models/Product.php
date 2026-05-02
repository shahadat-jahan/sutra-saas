<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use App\Traits\HasDynamicAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, HasUuid, MultiTenant, HasDynamicAttributes;

    protected $fillable = [
        'shop_id',
        'name',
        'sku',
        'purchase_price',
        'sale_price',
        'stock_quantity',
        'metadata',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'decimal:3',
            'metadata' => 'array',
            'attributes' => 'array',
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
