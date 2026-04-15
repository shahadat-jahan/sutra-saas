<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySummary extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'report_date',
        'total_sales',
        'total_expenses',
        'total_profit',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'total_sales' => 'decimal:2',
            'total_expenses' => 'decimal:2',
            'total_profit' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
