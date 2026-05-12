<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Domain\Models;

use App\Enums\InventoryLogType;
use App\Modules\Shared\Domain\Models\Shop;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * InventoryLog Model
 *
 * Part of the Inventory Module.
 */
final class InventoryLog extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'product_id',
        'quantity',
        'type',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'type' => InventoryLogType::class,
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
