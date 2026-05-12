<?php

declare(strict_types=1);

namespace App\Modules\Pos\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the POS module.
 */
final class PosServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
