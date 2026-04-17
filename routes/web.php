<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Common Routes
|--------------------------------------------------------------------------
*/

// Profile stays common or move to tenant if only accessible inside shop
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Main Domain Routes (sutra.localhost)
|--------------------------------------------------------------------------
*/
Route::domain(config('app.domain', 'localhost'))->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name('welcome');

    Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/shops', [\App\Http\Controllers\Admin\ShopController::class, 'index'])->name('shops.index');
        Route::patch('/shops/{shop}', [\App\Http\Controllers\Admin\ShopController::class, 'update'])->name('shops.update');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/settings', function () {
            return Inertia::render('Admin/Settings/Index');
        })->name('settings.index');
    });

    require __DIR__.'/auth.php';
});

/*
|--------------------------------------------------------------------------
| Tenant Subdomain Routes (*.sutra-saas.test)
|--------------------------------------------------------------------------
*/
Route::domain('{subdomain}.' . config('app.domain', 'localhost'))->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard', ['subdomain' => request()->route('subdomain')]);
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        // User Management for Shop Owners
        Route::prefix('settings')->name('tenant.')->group(function () {
            Route::get('/users', [\App\Http\Controllers\Tenant\UserController::class, 'index'])->name('users.index');
            Route::post('/users', [\App\Http\Controllers\Tenant\UserController::class, 'store'])->name('users.store');
            Route::delete('/users/{user}', [\App\Http\Controllers\Tenant\UserController::class, 'destroy'])->name('users.destroy');
        });
    });
});

