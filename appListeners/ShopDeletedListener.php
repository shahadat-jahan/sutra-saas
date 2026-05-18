<?php

namespace App\Listeners;

use App\Events\ShopDeletedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;

class ShopDeletedListener
{
    public function handle(ShopDeletedEvent $event): void
    {
        $shop = $event->shop;

        Log::info('Shop deleted', ['shop_id' => $shop->id]);

        // Delete associated users
        User::where('shop_id', $shop->id)->delete();

        // Delete shop-scoped roles and permissions
        $teamsKey = App::make(\Spatie\Permission\PermissionRegistrar::class)->teamsKey ?? 'team_id';
        Role::where($teamsKey, $shop->id)->delete();

        // Additional cleanup logic can be added here
    }
}