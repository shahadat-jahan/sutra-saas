<?php

use App\Modules\Dealer\Http\Controllers\DealerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('api/dealers')
    ->name('api.dealers.')
    ->group(function (): void {
        Route::post('/', [DealerController::class, 'store'])->name('store');
        Route::get('/{uuid}', [DealerController::class, 'show'])->name('show');
    });
