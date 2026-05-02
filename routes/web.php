<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ShopRoleController;
use App\Http\Controllers\Admin\ShopUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\RoleController as TenantRoleController;
use App\Http\Controllers\Tenant\UserController as TenantUserController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Common Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Theme Routes
|--------------------------------------------------------------------------
*/
Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');
Route::post('/theme/set', [ThemeController::class, 'set'])->name('theme.set');

/*
|--------------------------------------------------------------------------
| Main Domain Routes (sutra.localhost)
|--------------------------------------------------------------------------
*/
Route::domain(config('app.domain', 'localhost'))->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'plans' => \App\Models\Plan::where('is_active', true)->get(),
        ]);
    })->name('welcome');

    Route::middleware(['auth', 'verified', 'role:super-admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get(
                '/dashboard',
                [DashboardController::class, 'index']
            )->name('dashboard');
            Route::get('/announcements', [AnnouncementController::class, 'index'])
                ->name('announcements.index');
            Route::get('/announcements/create', [AnnouncementController::class, 'create'])
                ->name('announcements.create');
            Route::post('/announcements', [AnnouncementController::class, 'store'])
                ->name('announcements.store');
            Route::get(
                '/shops',
                [ShopController::class, 'index']
            )->name('shops.index');
            Route::post(
                '/shops',
                [ShopController::class, 'store']
            )->name('shops.store');
            Route::patch(
                '/shops/{shop}',
                [ShopController::class, 'update']
            )->name('shops.update');
            Route::delete(
                '/shops/{shop}',
                [ShopController::class, 'destroy']
            )->name('shops.destroy');

            Route::get('/shops/{shop}/users', [ShopUserController::class, 'index'])
                ->name('shops.users.index');
            Route::post('/shops/{shop}/users', [ShopUserController::class, 'store'])
                ->name('shops.users.store');
            Route::patch('/shops/{shop}/users/{user}', [ShopUserController::class, 'update'])
                ->name('shops.users.update');
            Route::delete('/shops/{shop}/users/{user}', [ShopUserController::class, 'destroy'])
                ->name('shops.users.destroy');

            Route::get('/shops/{shop}/roles', [ShopRoleController::class, 'index'])
                ->name('shops.roles.index');
            Route::post('/shops/{shop}/roles', [ShopRoleController::class, 'store'])
                ->name('shops.roles.store');
            Route::patch('/shops/{shop}/roles/{role}', [ShopRoleController::class, 'update'])
                ->name('shops.roles.update');
            Route::delete('/shops/{shop}/roles/{role}', [ShopRoleController::class, 'destroy'])
                ->name('shops.roles.destroy');
            Route::get(
                '/users',
                [UserController::class, 'index']
            )->name('users.index');
            Route::get('/settings', [SettingsController::class, 'index'])
                ->name('settings.index');
            Route::patch('/settings/module-pricing', [SettingsController::class, 'updateModulePricing'])
                ->name('settings.module-pricing.update');

            Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
            Route::patch('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
            Route::get('/plans/{plan}/logs', [PlanController::class, 'logs'])->name('plans.logs');
        });

    require __DIR__.'/auth.php';
});

/*
|--------------------------------------------------------------------------
| Tenant Subdomain Routes (*.sutra-saas.test)
|--------------------------------------------------------------------------
*/
Route::domain('{subdomain}.'.config('app.domain', 'localhost'))
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard', [
                'subdomain' => request()->route('subdomain'),
            ]);
        });

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Dashboard');
            })->name('dashboard');

            // User Management for Shop Owners
            Route::prefix('settings')
                ->middleware(['role:shop-owner'])
                ->name('tenant.')
                ->group(function () {
                    Route::get(
                        '/users',
                        [TenantUserController::class, 'index']
                    )->name('users.index');
                    Route::post(
                        '/users',
                        [TenantUserController::class, 'store']
                    )->name('users.store');
                    Route::patch(
                        '/users/{user}',
                        [TenantUserController::class, 'update']
                    )->name('users.update');
                    Route::delete(
                        '/users/{user}',
                        [TenantUserController::class, 'destroy']
                    )->name('users.destroy');

                    Route::get('/roles', [TenantRoleController::class, 'index'])
                        ->name('roles.index');
                    Route::post('/roles', [TenantRoleController::class, 'store'])
                        ->name('roles.store');
                    Route::patch('/roles/{role}', [TenantRoleController::class, 'update'])
                        ->name('roles.update');
                    Route::delete('/roles/{role}', [TenantRoleController::class, 'destroy'])
                        ->name('roles.destroy');
                });
        });

        require __DIR__.'/auth.php';
    });
