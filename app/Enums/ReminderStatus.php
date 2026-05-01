<?php

declare(strict_types=1);

namespace App\Enums;

enum ReminderStatus: int
{
    case PENDING = 1;
    case SENT = 2;
    case FAILED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SENT => 'Sent',
            self::FAILED => 'Failed',
        };
    }
}
