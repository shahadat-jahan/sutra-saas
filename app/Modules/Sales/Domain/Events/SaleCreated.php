<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Events;

use App\Modules\Sales\Domain\Models\Sale;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Sale $sale) {}
}
