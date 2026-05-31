<?php

namespace App\Events;

use App\Models\Shop;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShopDeletedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public Shop $shop)
    {
        //
    }
}