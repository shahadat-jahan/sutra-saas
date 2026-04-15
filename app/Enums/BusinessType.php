<?php

declare(strict_types=1);

namespace App\Enums;

enum BusinessType: int
{
    case RETAIL = 1;
    case PHARMACY = 2;

    public function label(): string
    {
        return match($this) {
            self::RETAIL => 'Retail Shop',
            self::PHARMACY => 'Pharmacy',
        };
    }
}
