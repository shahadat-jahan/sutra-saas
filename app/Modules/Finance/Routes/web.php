<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function (): void {
        //
    });
