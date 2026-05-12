<?php

namespace App\Traits;

use App\Support\Scopes\ShopScope;
use App\Modules\Shared\Domain\Models\User;
use App\Support\TenantManager;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void creating(\Closure $callback)
 * @method static void addGlobalScope(\Illuminate\Database\Eloquent\Scope|\Closure|string $scope, \Illuminate\Database\Eloquent\Scope|\Closure|null $implementation = null)
 */
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
