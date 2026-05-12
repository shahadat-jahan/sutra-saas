<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $modulesPath = app_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $modulePath) {
            $this->loadModuleRoutes($modulePath);
            $this->registerModuleProvider($modulePath);
        }
    }

    /**
     * Register the service provider for a module if it exists.
     */
    protected function registerModuleProvider(string $modulePath): void
    {
        $moduleName = basename($modulePath);
        $providerClass = "App\\Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";

        if (class_exists($providerClass)) {
            $this->app->register($providerClass);
        }
    }

    protected function loadModuleRoutes(string $modulePath): void
    {
        $webRoutesPath = $modulePath.'/Routes/web.php';
        $apiRoutesPath = $modulePath.'/Routes/api.php';

        if (File::exists($webRoutesPath)) {
            Route::middleware('web')->group($webRoutesPath);
        }

        if (File::exists($apiRoutesPath)) {
            Route::middleware('api')
                ->prefix('api')
                ->group($apiRoutesPath);
        }
    }
}
