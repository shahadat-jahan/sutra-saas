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

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

