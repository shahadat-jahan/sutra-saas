<?php

namespace App\Traits;

use App\Models\Scopes\ShopScope;

trait Multitenant
{
    protected static function bootMultitenant(): void
    {
        static::addGlobalScope(new ShopScope);

        static::creating(function ($model): void {
            if (auth()->check() && blank($model->shop_id)) {
                $model->shop_id = auth()->user()->shop_id;
            }
        });
    }
}
