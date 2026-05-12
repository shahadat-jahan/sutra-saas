<?php

declare(strict_types=1);

namespace App\Modules\Sales\Providers;

use App\Modules\Sales\Infrastructure\Repositories\Eloquent\CustomerRepository;
use App\Modules\Sales\Infrastructure\Repositories\Eloquent\SaleRepository;
use App\Modules\Sales\Infrastructure\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Modules\Sales\Infrastructure\Repositories\Interfaces\SaleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Sales module.
 */
final class SalesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
