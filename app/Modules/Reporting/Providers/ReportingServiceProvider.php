<?php

declare(strict_types=1);

namespace App\Modules\Reporting\Providers;

use App\Modules\Reporting\Infrastructure\Repositories\Eloquent\DailySummaryRepository;
use App\Modules\Reporting\Infrastructure\Repositories\Interfaces\DailySummaryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Reporting module.
 */
final class ReportingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DailySummaryRepositoryInterface::class, DailySummaryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
