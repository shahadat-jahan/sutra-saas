<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('pos')
    ->name('pos.')
    ->group(function (): void {
        Route::get('/', [\App\Modules\Pos\Http\Controllers\PosController::class, 'index'])->name('index');
        Route::post('/', [\App\Modules\Pos\Http\Controllers\PosController::class, 'store'])->name('store');
    });
