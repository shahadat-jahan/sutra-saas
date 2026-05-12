<?php

declare(strict_types=1);

namespace App\Modules\Discount\Providers;

use App\Modules\Discount\Domain\Listeners\ApplyDiscountsToOrder;
use App\Modules\Sales\Domain\Events\OrderTotalCalculating;
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
        //
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

        // Load module migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
