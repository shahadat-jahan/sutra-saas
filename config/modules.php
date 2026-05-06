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
            'monthly_price_bdt' => 1500,
            'monthly_price_usd' => 15,
        ],
        'inventory' => [
            'name' => 'Inventory',
            'monthly_price_bdt' => 700,
            'monthly_price_usd' => 7,
        ],
        'finance' => [
            'name' => 'Finance',
            'monthly_price_bdt' => 900,
            'monthly_price_usd' => 9,
        ],
    ],
];
