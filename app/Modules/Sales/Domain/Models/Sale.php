<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Models;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Events\SaleProcessed;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Sale Model
 *
 * Part of the Sales Module.
 */
final class Sale extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'shop_id',
        'customer_id',
        'user_id',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'status',
        'metadata',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'saved' => SaleProcessed::class,
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
            'metadata' => 'array',
            'payment_method' => PaymentMethod::class,
            'status' => SaleStatus::class,
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
