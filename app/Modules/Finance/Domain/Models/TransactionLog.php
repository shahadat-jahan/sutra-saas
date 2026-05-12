<?php

declare(strict_types=1);

namespace App\Modules\Finance\Domain\Models;

use App\Enums\PaymentMethod;
use App\Enums\TransactionLogType;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TransactionLog Model
 * 
 * Part of the Finance Module.
 */
final class TransactionLog extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'user_id',
        'amount',
        'type',
        'payment_method',
        'reference_id',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type' => TransactionLogType::class,
            'payment_method' => PaymentMethod::class,
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
