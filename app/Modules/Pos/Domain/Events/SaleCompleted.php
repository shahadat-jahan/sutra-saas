<?php

namespace App\Modules\Pos\Domain\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Dispatched when a sale is fully completed at POS.
 *
 * This event is consumed by listeners in Inventory, Finance,
 * and Reporting modules to keep the system in sync without
 * tight coupling between modules.
 */
class SaleCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  int  $shopId  The tenant shop ID.
     * @param  int  $userId  The user who processed the sale.
     * @param  array<int, array{product_id: int, quantity: float, unit_price: float, subtotal: float}>  $items  Sold items.
     * @param  float  $totalAmount  Total sale amount.
     * @param  string  $paymentMethod  Payment method used (cash, card, mobile, etc.).
     * @param  string|null  $referenceId  Optional external reference (invoice number, etc.).
     */
    public function __construct(
        public readonly int $shopId,
        public readonly int $userId,
        public readonly array $items,
        public readonly float $totalAmount,
        public readonly string $paymentMethod,
        public readonly ?string $referenceId = null,
    ) {}
}
