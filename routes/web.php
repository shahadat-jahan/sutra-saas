<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Tenant\UserController as TenantUserController;
use App\Http\Controllers\ProfileController;
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
        return Inertia::render('Welcome');
    })->name('welcome');

    Route::middleware(['auth', 'verified', 'role:super-admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get(
                '/dashboard',
                [DashboardController::class, 'index']
            )->name('dashboard');
            Route::get(
                '/shops',
                [ShopController::class, 'index']
            )->name('shops.index');
            Route::patch(
                '/shops/{shop}',
                [ShopController::class, 'update']
            )->name('shops.update');
            Route::get(
                '/users',
                [UserController::class, 'index']
            )->name('users.index');
            Route::get('/settings', function () {
                return Inertia::render('Admin/Settings/Index');
            })->name('settings.index');
        });

    require __DIR__ . '/auth.php';
});

/*
|--------------------------------------------------------------------------
| Tenant Subdomain Routes (*.sutra-saas.test)
|--------------------------------------------------------------------------
*/
Route::domain('{subdomain}.' . config('app.domain', 'localhost'))
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
                    Route::delete(
                        '/users/{user}',
                        [TenantUserController::class, 'destroy']
                    )->name('users.destroy');
                });
        });
    });
