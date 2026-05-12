<?php

use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\ModuleServiceProvider;
use App\Modules\Discount\Providers\DiscountServiceProvider;

return [
    AppServiceProvider::class,
    EventServiceProvider::class,
    ModuleServiceProvider::class,
    DiscountServiceProvider::class,
];
