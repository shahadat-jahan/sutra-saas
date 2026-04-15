<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('inventory')
    ->name('api.inventory.')
    ->group(function (): void {
        //
    });
