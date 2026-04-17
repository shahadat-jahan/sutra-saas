<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Eloquent\DailySummaryRepository;
use App\Repositories\Eloquent\InventoryLogRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\ShopRepository;
use App\Repositories\Eloquent\TransactionLogRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\DailySummaryRepositoryInterface;
use App\Repositories\Interfaces\InventoryLogRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ShopRepositoryInterface;
use App\Repositories\Interfaces\TransactionLogRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Support\TenantManager;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton for TenantManager
        $this->app->singleton(TenantManager::class, function () {
            return new TenantManager();
        });

        // Repository bindings
        $this->app->bind(ShopRepositoryInterface::class, ShopRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(TransactionLogRepositoryInterface::class, TransactionLogRepository::class);
        $this->app->bind(InventoryLogRepositoryInterface::class, InventoryLogRepository::class);
        $this->app->bind(DailySummaryRepositoryInterface::class, DailySummaryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
