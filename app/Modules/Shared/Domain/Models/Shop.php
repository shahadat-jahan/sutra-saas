<?php

namespace App\Modules\Shared\Domain\Models;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'business_type',
        'logo_path',
        'plan_id',
        'enabled_modules',
        'is_free',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enabled_modules' => 'array',
            'is_free' => 'boolean',
            'status' => ActiveStatus::class,
            'business_type' => BusinessType::class,
        ];
    }

    protected static function booted()
    {
        static::creating(function ($shop) {
            $slug = Str::slug($shop->name);

            $count = static::query()->where('slug', 'LIKE', "{$slug}%", 'and')->count();
            $shop->slug = $count ? "{$slug}-".($count + 1) : $slug;
            if (empty($shop->enabled_modules)) {
                $shop->enabled_modules = ['inventory'];
            }
        });
    }

    /**
     * @return array<string, array{name: string, monthly_price_bdt: int, monthly_price_usd: int}>
     */
    public static function moduleCatalog(): array
    {
        /** @var array<string, array{name: string, monthly_price_bdt: int, monthly_price_usd: int}> $catalog */
        $catalog = config('modules.catalog', []);

        // During fresh bootstrap (before running migrations) this table may not exist.
        if (! Schema::hasTable('system_settings')) {
            return $catalog;
        }

        /** @var array<string, array{name?: string, monthly_price?: int|string}> $override */
        $override = SystemSetting::getValue('module_catalog', []);

        if (! is_array($override)) {
            return $catalog;
        }

        foreach ($override as $moduleKey => $moduleConfig) {
            if (! isset($catalog[$moduleKey]) || ! is_array($moduleConfig)) {
                continue;
            }

            $catalog[$moduleKey]['monthly_price_bdt'] = (int) ($moduleConfig['monthly_price_bdt'] ?? $catalog[$moduleKey]['monthly_price_bdt']);
            $catalog[$moduleKey]['monthly_price_usd'] = (int) ($moduleConfig['monthly_price_usd'] ?? $catalog[$moduleKey]['monthly_price_usd']);
        }

        return $catalog;
    }

    public function monthlyPrice(): int
    {
        if ($this->is_free) {
            return 0;
        }

        // If shop has an assigned plan, use the plan's BDT price
        if ($this->plan_id) {
            return (int) optional($this->plan)->price_bdt;
        }

        // Fallback to module-based pricing
        $catalog = self::moduleCatalog();
        $selectedModules = $this->enabled_modules ?? [];

        $total = 0;
        foreach ($selectedModules as $moduleKey) {
            $total += (int) ($catalog[$moduleKey]['monthly_price_bdt'] ?? 0);
        }

        return $total;
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
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
