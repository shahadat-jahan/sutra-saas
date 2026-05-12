<?php

declare(strict_types=1);

namespace App\Enums;

enum DiscountType: int
{
    case FIXED = 1;
    case PERCENTAGE = 2;

    public function label(): string
    {
        return match($this) {
            self::FIXED => 'Fixed Amount',
            self::PERCENTAGE => 'Percentage',
        };
    }
}
