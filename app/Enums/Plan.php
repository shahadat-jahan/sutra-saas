<?php

declare(strict_types=1);

namespace App\Enums;

enum Plan: int
{
    case BASIC = 1;
    case PROFESSIONAL = 2;
    case ENTERPRISE = 3;

    public function label(): string
    {
        return match($this) {
            self::BASIC => 'Basic (Starter)',
            self::PROFESSIONAL => 'Professional',
            self::ENTERPRISE => 'Enterprise',
        };
    }

    public function price(string $currency = 'USD'): string
    {
        return match($this) {
            self::BASIC => $currency === 'BDT' ? '৳১,০০০' : '$10',
            self::PROFESSIONAL => $currency === 'BDT' ? '৳২,০০০' : '$20',
            self::ENTERPRISE => 'Custom',
        };
    }

    public function modules(): array
    {
        return match($this) {
            self::BASIC => ['pos', 'inventory'],
            self::PROFESSIONAL => ['pos', 'inventory', 'analytics', 'custom_subdomain', 'team_management'],
            self::ENTERPRISE => ['pos', 'inventory', 'analytics', 'custom_subdomain', 'team_management', 'api_access', 'dedicated_support'],
        };
    }
}
