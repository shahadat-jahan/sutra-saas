<?php

namespace App\Traits;

use App\Models\Scopes\ShopScope;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Support\Facades\Auth;

trait MultiTenant
{
    /** @var User */
    protected static function bootMultiTenant(): void
    {
        static::addGlobalScope(new ShopScope);

        static::creating(function ($model): void {
            if (blank($model->shop_id)) {
                $tenantId = app(TenantManager::class)->getTenantId() ?? Auth::user()?->shop_id;

                if ($tenantId) {
                    $model->shop_id = $tenantId;
                }
            }
        });
    }
}
