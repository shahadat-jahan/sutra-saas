<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulePriceLog extends Model
{
    protected $fillable = [
        'module_key',
        'user_id',
        'old_price_bdt',
        'new_price_bdt',
        'old_price_usd',
        'new_price_usd',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
