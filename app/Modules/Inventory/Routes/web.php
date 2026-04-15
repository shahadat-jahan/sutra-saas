<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('inventory')
    ->name('inventory.')
    ->group(function (): void {
        //
    });
