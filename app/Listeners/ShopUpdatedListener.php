<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ShopUpdatedEvent;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Notifications\ShopUpdatedNotification;
use Illuminate\Support\Facades\Log;

class ShopUpdatedListener
{
    public function handle(ShopUpdatedEvent $event): void
    {
        $shop = $event->shop;
        $data = $event->updatedData;

        Log::info('Shop updated', [
            'shop_id' => $shop->id,
            'changes' => $data
        ]);

        // Find the primary shop owner (the first user created for this shop)
        /** @var \App\Modules\Shared\Domain\Models\User|null $owner */
        $owner = User::where('shop_id', $shop->id)->orderBy('id', 'asc')->first();

        if ($owner) {
            $owner->notify(new ShopUpdatedNotification(
                appName: (string) config('app.name', 'Sutra'),
                shop: $shop,
                changes: $data
            ));
        }
    }
}
