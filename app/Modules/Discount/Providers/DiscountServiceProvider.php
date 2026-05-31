<?php

declare(strict_types=1);

namespace App\Modules\Discount\Providers;

use App\Modules\Discount\Domain\Listeners\ApplyDiscountsToOrder;
use App\Modules\Discount\Domain\Listeners\ApplySlabDiscount;
use App\Modules\Discount\Infrastructure\Repositories\Eloquent\DiscountRuleRepository;
use App\Modules\Discount\Infrastructure\Repositories\Interfaces\DiscountRuleRepositoryInterface;
use App\Modules\Sales\Domain\Events\OrderTotalCalculating;
use App\Modules\Sales\Domain\Events\SaleCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Discount module.
 *
 * Handles registration of listeners and loading of module-specific resources.
 */
final class DiscountServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DiscountRuleRepositoryInterface::class, DiscountRuleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the listener for order total calculation
        // This is the core of the event-driven decoupling
        Event::listen(
            OrderTotalCalculating::class,
            ApplyDiscountsToOrder::class
        );

        Event::listen(
            SaleCreated::class,
            ApplySlabDiscount::class
        );

        // Load module migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
