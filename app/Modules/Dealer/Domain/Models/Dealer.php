<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Domain\Models;

use App\Modules\Dealer\Domain\Enums\DealerStatus;
use App\Modules\Dealer\Domain\Enums\DealerTier;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Dealer Model
 *
 * Represents a downstream business entity (retailer, sub-distributor,
 * or institutional buyer) managed by the tenant distributor.
 *
 * Part of the Dealer Module.
 */
final class Dealer extends Model
{
    use HasFactory, HasUuid, MultiTenant, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'user_id',
        'commission_plan_id',
        'business_name',
        'contact_person',
        'phone',
        'email',
        'address',
        'trade_license',
        'tin',
        'credit_limit',
        'outstanding_balance',
        'payment_terms',
        'tier',
        'territory',
        'metadata',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'outstanding_balance' => 'decimal:2',
            'tier' => DealerTier::class,
            'status' => DealerStatus::class,
            'metadata' => 'array',
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

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(DealerLedger::class);
    }

    /**
     * Get the available credit for this dealer.
     */
    public function availableCredit(): float
    {
        return (float) $this->credit_limit - (float) $this->outstanding_balance;
    }

    /**
     * Check if the dealer can afford a given amount on credit.
     */
    public function canAfford(float $amount): bool
    {
        return $this->availableCredit() >= $amount;
    }
}
