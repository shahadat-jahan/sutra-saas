<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: int
{
    case CASH = 1;
    case CARD = 2;
    case MOBILE = 3;
    case CREDIT = 4;

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::CARD => 'Card',
            self::MOBILE => 'Mobile Payment',
            self::CREDIT => 'Credit (Baki)',
        };
    }
}
