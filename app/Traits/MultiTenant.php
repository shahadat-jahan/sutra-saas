<?php

namespace App\Traits;

use App\Modules\Shared\Domain\Models\User;
use App\Support\Scopes\ShopScope;
use App\Support\TenantManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Model
 *
 * @method static void creating(\Closure $callback)
 * @method static void updating(\Closure $callback)
 * @method static void addGlobalScope(\Illuminate\Database\Eloquent\Scope|\Closure|string $scope, \Illuminate\Database\Eloquent\Scope|\Closure|null $implementation = null)
 */
trait MultiTenant
{
    /** @var User */
    protected static function bootMultiTenant(): void
    {
        static::addGlobalScope(new ShopScope);

        static::creating(static function ($model): void {
            if (blank($model->shop_id)) {
                $tenantId = app(TenantManager::class)->getTenantId() ?? Auth::user()?->shop_id;

                if ($tenantId) {
                    $model->shop_id = $tenantId;
                }
            }
        });

        static::updating(static function ($model): void {
            if ($model->isDirty('shop_id')) {
                $model->shop_id = $model->getOriginal('shop_id');
            }
        });
    }
}
