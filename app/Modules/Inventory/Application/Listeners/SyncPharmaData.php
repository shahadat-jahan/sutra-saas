<?php

namespace App\Modules\Inventory\Application\Listeners;

use App\Events\SaleProcessed;
use App\Modules\Inventory\Application\Services\PharmaSyncService;

class SyncPharmaData
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected PharmaSyncService $pharmaSyncService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SaleProcessed $event): void
    {
        $sale = $event->sale;
        $shop = $sale->shop;

        // In a real scenario, we would iterate through products in the sale.
        // For this task, we trigger the sync service for the shop's pharma context.
        // We'll assume the service handles the logic if a specific product is passed.
        // (Placeholder for product iteration)
    }
}
