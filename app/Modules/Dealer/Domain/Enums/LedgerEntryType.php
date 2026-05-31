<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Domain\Enums;

/**
 * Ledger entry type enum — debit increases dealer's outstanding, credit reduces it.
 */
enum LedgerEntryType: int
{
    case DEBIT = 1;
    case CREDIT = 2;

    public function label(): string
    {
        return match ($this) {
            self::DEBIT => 'Debit',
            self::CREDIT => 'Credit',
        };
    }
}
