<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Module Catalog
    |--------------------------------------------------------------------------
    |
    | Central place to manage billable modules and monthly pricing.
    | POS remains mandatory during shop create/update validation.
    |
    */
    'catalog' => [
        'pos' => [
            'name' => 'POS',
            'monthly_price' => 1500,
        ],
        'inventory' => [
            'name' => 'Inventory',
            'monthly_price' => 700,
        ],
        'finance' => [
            'name' => 'Finance',
            'monthly_price' => 900,
        ],
    ],
];
