<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use App\Traits\HasDynamicAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, HasUuid, MultiTenant, HasDynamicAttributes;

    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'email',
        'address',
        'nid',
        'credit_limit',
        'current_balance',
        'profile_data',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'profile_data' => 'array',
            'status' => \App\Enums\ActiveStatus::class,
        ];
    }

    /**
     * Scope for active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', \App\Enums\ActiveStatus::ACTIVE);
    }

    /**
     * Define the JSONB column that stores dynamic attributes.
     */
    protected function getDynamicAttributesColumn(): string
    {
        return 'profile_data';
    }

    /**
     * Relationship with the Shop (Tenant).
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Relationship with Transaction Logs.
     */
    public function transactionLogs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }
}
