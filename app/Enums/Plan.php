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
        $appName = config('app.name', 'Sutra');

        return match ($this) {
            self::BASIC => $appName.' Basic',
            self::PROFESSIONAL => $appName.' Pro',
            self::ENTERPRISE => $appName.' Enterprise',
        };
    }

    public function price(string $currency = 'BDT'): string
    {
        return match ($this) {
            self::BASIC => $currency === 'BDT' ? '৳500' : '$8',
            self::PROFESSIONAL => $currency === 'BDT' ? '৳1,500' : '$15',
            self::ENTERPRISE => 'Custom',
        };
    }

    public function modules(): array
    {
        return match ($this) {
            self::BASIC => [
                // MVP Phase 1
                'inventory',
            ],

            self::PROFESSIONAL => [
                // MVP Phase 2
                'pos',
                'inventory',
                'basic_reports',
            ],

            self::ENTERPRISE => [
                // MVP Phase 3
                'pos',
                'inventory',
                'finance',
                'customization',
                'basic_reports',
            ],
        };
    }

    public function hasModule(string $moduleName): bool
    {
        return in_array($moduleName, $this->modules());
    }
}
