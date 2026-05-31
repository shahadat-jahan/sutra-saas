<?php

namespace App\Listeners;

use App\Events\ShopUpdatedEvent;
use App\Models\Shop;
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

        // Additional logic for shop updates can be added here
        // For example: notifications, cache invalidation, etc.
    }
}