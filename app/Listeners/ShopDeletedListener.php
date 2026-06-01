<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ShopDeletedEvent;
use App\Modules\Shared\Domain\Models\User;
use App\Notifications\ShopDeletedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;

class ShopDeletedListener
{
    public function handle(ShopDeletedEvent $event): void
    {
        $shop = $event->shop;

        Log::info('Shop deleted', ['shop_id' => $shop->id]);

        // Find the primary shop owner to notify them before deleting
        /** @var \App\Modules\Shared\Domain\Models\User|null $owner */
        $owner = User::where('shop_id', $shop->id)->orderBy('id', 'asc')->first();

        if ($owner) {
            // Send notification using Notification::route because the user model will be deleted
            Notification::route('mail', $owner->email)
                ->notify(new ShopDeletedNotification(
                    appName: (string) config('app.name', 'Sutra'),
                    shopName: $shop->name,
                    ownerName: $owner->name
                ));
        }

        // Delete associated users
        User::where('shop_id', $shop->id)->delete();

        // Delete shop-scoped roles and permissions
        $teamsKey = App::make(\Spatie\Permission\PermissionRegistrar::class)->teamsKey ?? 'team_id';
        Role::where($teamsKey, $shop->id)->delete();
    }
}
