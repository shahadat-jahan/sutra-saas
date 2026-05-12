<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Providers;

use App\Modules\Inventory\Infrastructure\Repositories\Eloquent\InventoryLogRepository;
use App\Modules\Inventory\Infrastructure\Repositories\Eloquent\ProductRepository;
use App\Modules\Inventory\Infrastructure\Repositories\Interfaces\InventoryLogRepositoryInterface;
use App\Modules\Inventory\Infrastructure\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Inventory module.
 */
final class InventoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(InventoryLogRepositoryInterface::class, InventoryLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
