<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

final class Plan extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'price_bdt',
        'price_usd',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price_bdt' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::updated(function (Plan $plan) {
            if ($plan->isDirty(['price_bdt', 'price_usd'])) {
                PlanPriceLog::create([
                    'plan_id' => $plan->id,
                    'user_id' => Auth::id() ?? 1, // Fallback to system/first user if no auth (e.g. seeder)
                    'old_price_bdt' => $plan->getOriginal('price_bdt'),
                    'new_price_bdt' => $plan->price_bdt,
                    'old_price_usd' => $plan->getOriginal('price_usd'),
                    'new_price_usd' => $plan->price_usd,
                ]);
            }
        });
    }

    public function priceLogs(): HasMany
    {
        return $this->hasMany(PlanPriceLog::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    /**
     * Get price based on currency or country.
     */
    public function getFormattedPrice(?string $currency = null): string
    {
        if (!$currency) {
            // Default logic: BD gets BDT, others get USD
            // This could be enhanced with a GeoIP library
            $currency = request()->header('CF-IPCountry') === 'BD' ? 'BDT' : 'USD';
        }

        if ($currency === 'BDT') {
            return '৳' . number_format((float)$this->price_bdt, 0);
        }

        return '$' . number_format((float)$this->price_usd, 2);
    }
}
