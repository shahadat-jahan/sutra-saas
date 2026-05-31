<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PlanPriceLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'plan_id',
        'user_id',
        'old_price_bdt',
        'new_price_bdt',
        'old_price_usd',
        'new_price_usd',
    ];

    protected $casts = [
        'old_price_bdt' => 'decimal:2',
        'new_price_bdt' => 'decimal:2',
        'old_price_usd' => 'decimal:2',
        'new_price_usd' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
