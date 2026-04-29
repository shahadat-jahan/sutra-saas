<?php

declare(strict_types=1);

namespace App\Enums;

enum InventoryLogType: int
{
    case IN = 1;
    case OUT = 2;
    case ADJUSTMENT = 3;
    case RETURN = 4;

    public function label(): string
    {
        return match ($this) {
            self::IN => 'Stock In',
            self::OUT => 'Stock Out',
            self::ADJUSTMENT => 'Adjustment',
            self::RETURN => 'Return',
        };
    }
}
