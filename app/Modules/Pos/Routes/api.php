<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('pos')
    ->name('api.pos.')
    ->group(function (): void {
        //
    });
