<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Domain\Enums;

/**
 * Dealer status enum.
 */
enum DealerStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SUSPENDED = 3;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
        };
    }
}
