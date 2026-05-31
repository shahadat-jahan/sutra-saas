<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Providers;

use App\Modules\Dealer\Infrastructure\Repositories\Eloquent\DealerLedgerRepository;
use App\Modules\Dealer\Infrastructure\Repositories\Eloquent\DealerRepository;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerLedgerRepositoryInterface;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Dealer module.
 */
final class DealerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DealerRepositoryInterface::class, DealerRepository::class);
        $this->app->bind(DealerLedgerRepositoryInterface::class, DealerLedgerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
