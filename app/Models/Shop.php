<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = [
        'name',
        'slug',
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


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            $slug = Str::slug($shop->name);

            $count = static::where('slug', 'LIKE', "{$slug}%")->count();
            $shop->slug = $count ? "{$slug}-" . ($count + 1) : $slug;
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
}
