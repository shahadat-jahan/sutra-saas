<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'name',
        'business_type',
        'logo_path',
        'enabled_modules',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enabled_modules' => 'array',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function transactionLogs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }

    public function dailySummaries(): HasMany
    {
        return $this->hasMany(DailySummary::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
