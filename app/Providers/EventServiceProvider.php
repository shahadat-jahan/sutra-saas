<?php

namespace App\Providers;

use App\Modules\Finance\Application\Listeners\RecordTransaction;
use App\Modules\Inventory\Application\Listeners\UpdateInventory;
use App\Modules\Pos\Domain\Events\SaleCompleted;
use App\Modules\Reporting\Application\Listeners\UpdateDailySummary;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * Each event maps to listeners across module boundaries,
     * keeping modules decoupled while maintaining explicit wiring.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SaleCompleted::class => [
            UpdateInventory::class,
            RecordTransaction::class,
            UpdateDailySummary::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
