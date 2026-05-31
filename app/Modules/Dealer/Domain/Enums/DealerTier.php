<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Domain\Enums;

/**
 * Dealer tier classification enum.
 */
enum DealerTier: int
{
    case BRONZE = 1;
    case SILVER = 2;
    case GOLD = 3;

    public function label(): string
    {
        return match ($this) {
            self::BRONZE => 'Bronze',
            self::SILVER => 'Silver',
            self::GOLD => 'Gold',
        };
    }
}
