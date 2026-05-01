<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionLogType: int
{
    case INCOME = 1;
    case EXPENSE = 2;

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'Income',
            self::EXPENSE => 'Expense',
        };
    }
}
