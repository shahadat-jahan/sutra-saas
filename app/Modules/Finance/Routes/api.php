<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('finance')
    ->name('api.finance.')
    ->group(function (): void {
        //
    });
