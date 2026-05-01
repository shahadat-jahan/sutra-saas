<?php

declare(strict_types=1);

namespace App\Enums;

enum SaleStatus: int
{
    case PAID = 1;
    case PARTIAL = 2;
    case CREDIT = 3;

    public function label(): string
    {
        return match ($this) {
            self::PAID => 'Paid',
            self::PARTIAL => 'Partial',
            self::CREDIT => 'Credit',
        };
    }
}
