<?php

namespace App\Traits;

use App\Models\Scopes\ShopScope;
use Illuminate\Support\Facades\Auth;

trait MultiTenant
{
    /** @var \App\Models\User $user */
    protected static function bootMultiTenant(): void
    {
        static::addGlobalScope(new ShopScope);

        static::creating(function ($model): void {
            if (Auth::check() && blank($model->shop_id)) {
                $user = Auth::user();
                $model->shop_id = $user->shop_id;
            }
        });
    }
}
