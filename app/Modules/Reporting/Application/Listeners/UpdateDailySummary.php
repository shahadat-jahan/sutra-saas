<?php

namespace App\Modules\Reporting\Application\Listeners;

use App\Models\DailySummary;
use App\Modules\Pos\Domain\Events\SaleCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Updates or creates the daily reporting summary
 * when a sale is completed.
 */
class UpdateDailySummary implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     */
    public int $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(SaleCompleted $event): void
    {
        $today = now()->toDateString();

        // Calculate total cost of goods sold for profit estimation
        $totalCost = collect($event->items)->sum(function (array $item) {
            return ($item['unit_price'] * $item['quantity']);
        });

        DB::transaction(function () use ($event, $today, $totalCost) {
            $summary = DailySummary::firstOrCreate(
                [
                    'shop_id' => $event->shopId,
                    'report_date' => $today,
                ],
                [
                    'total_sales' => 0,
                    'total_expenses' => 0,
                    'total_profit' => 0,
                ]
            );

            $summary->increment('total_sales', $event->totalAmount);
            $summary->increment('total_profit', $event->totalAmount);
        });

        Log::info('UpdateDailySummary: Daily report updated.', [
            'shop_id' => $event->shopId,
            'date' => $today,
            'sale_amount' => $event->totalAmount,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(SaleCompleted $event, \Throwable $exception): void
    {
        Log::error('UpdateDailySummary: Failed to update daily summary.', [
            'shop_id' => $event->shopId,
            'error' => $exception->getMessage(),
        ]);
    }
}
