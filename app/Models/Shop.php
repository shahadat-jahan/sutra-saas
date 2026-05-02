<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Enums\Plan;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'business_type',
        'plan',
        'logo_path',
        'enabled_modules',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enabled_modules' => 'array',
            'status' => ActiveStatus::class,
            'business_type' => BusinessType::class,
            'plan' => Plan::class,
        ];
    }

    protected static function booted()
    {
        static::creating(function ($shop) {
            $slug = Str::slug($shop->name);

            $count = static::query()->where('slug', 'LIKE', "{$slug}%", 'and')->count();
            $shop->slug = $count ? "{$slug}-".($count + 1) : $slug;
            if (empty($shop->enabled_modules)) {
                $shop->enabled_modules = ['pos'];
            }
        });
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

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
