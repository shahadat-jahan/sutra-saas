<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Domain\Models;

use App\Modules\Dealer\Domain\Enums\LedgerEntryType;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DealerLedger Model
 *
 * Immutable log of every credit/debit transaction for a dealer.
 * Supports running balance tracking for fast balance lookups.
 *
 * Part of the Dealer Module.
 */
final class DealerLedger extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $table = 'dealer_ledgers';

    protected $fillable = [
        'shop_id',
        'dealer_id',
        'user_id',
        'type',
        'amount',
        'running_balance',
        'reference_type',
        'reference_id',
        'idempotency_key',
        'narration',
        'metadata',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'type' => LedgerEntryType::class,
            'amount' => 'decimal:2',
            'running_balance' => 'decimal:2',
            'metadata' => 'array',
            'transaction_date' => 'datetime',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
