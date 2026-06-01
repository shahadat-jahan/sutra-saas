<?php

namespace App\Events;

use App\Modules\Shared\Domain\Models\Shop;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShopCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Shop $shop,
        public array $data,
        public string $ownerPassword
    ) {}
}