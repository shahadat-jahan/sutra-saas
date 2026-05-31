<?php

declare(strict_types=1);

namespace App\Modules\Finance\Providers;

use App\Modules\Finance\Infrastructure\Repositories\Eloquent\TransactionLogRepository;
use App\Modules\Finance\Infrastructure\Repositories\Interfaces\TransactionLogRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Finance module.
 */
final class FinanceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransactionLogRepositoryInterface::class, TransactionLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
