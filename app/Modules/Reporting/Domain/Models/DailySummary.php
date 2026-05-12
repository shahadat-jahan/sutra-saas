<?php

declare(strict_types=1);

namespace App\Modules\Reporting\Domain\Models;

use App\Modules\Shared\Domain\Models\Shop;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DailySummary Model
 * 
 * Part of the Reporting Module.
 */
final class DailySummary extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'report_date',
        'total_sales',
        'total_expenses',
        'total_profit',
        'total_tax',
        'total_vat',
        'total_discount',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'total_sales' => 'decimal:2',
            'total_expenses' => 'decimal:2',
            'total_profit' => 'decimal:2',
            'total_tax' => 'decimal:2',
            'total_vat' => 'decimal:2',
            'total_discount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
