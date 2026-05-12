<?php

declare(strict_types=1);

namespace App\Modules\Shared\Providers;

use App\Modules\Shared\Infrastructure\Repositories\Eloquent\ShopRepository;
use App\Modules\Shared\Infrastructure\Repositories\Eloquent\UserRepository;
use App\Modules\Shared\Infrastructure\Repositories\Interfaces\ShopRepositoryInterface;
use App\Modules\Shared\Infrastructure\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Shared module.
 */
final class SharedServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ShopRepositoryInterface::class, ShopRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
