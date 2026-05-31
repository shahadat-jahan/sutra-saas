<?php

declare(strict_types=1);

namespace App\Modules\Sales\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event fired when calculating order totals.
 *
 * This allows other modules (like Discount) to hook into the calculation
 * process and modify the discount_amount or other properties.
 */
final class OrderTotalCalculating
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  object  $order  The order object being calculated (must have items and discount_amount property)
     */
    public function __construct(
        public object $order
    ) {}
}
