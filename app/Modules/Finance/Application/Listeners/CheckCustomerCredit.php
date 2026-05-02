<?php

namespace App\Modules\Finance\Application\Listeners;

use App\Events\SaleProcessed;
use App\Modules\Finance\Application\Services\BakirKhataService;

class CheckCustomerCredit
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected BakirKhataService $bakirKhataService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SaleProcessed $event): void
    {
        $sale = $event->sale;

        // Only validate if there is a customer (not a walk-in sale)
        if ($sale->customer) {
            $this->bakirKhataService->canExtendCredit(
                $sale->customer,
                (float) $sale->total_amount
            );
        }
    }
}
