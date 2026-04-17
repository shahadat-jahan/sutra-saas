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
        return match ($this) {
            self::BASIC => 'Sutra Basic',
            self::PROFESSIONAL => 'Sutra Pro',
            self::ENTERPRISE => 'Sutra Enterprise',
        };
    }

    public function price(string $currency = 'BDT'): string
    {
        return match ($this) {
            self::BASIC => $currency === 'BDT' ? '৳৮৫০' : '$8',
            self::PROFESSIONAL => $currency === 'BDT' ? '৳১,৫০০' : '$15',
            self::ENTERPRISE => 'Custom',
        };
    }

    public function modules(): array
    {
        return match ($this) {
            self::BASIC => [
                'pos',
                'inventory',
                'basic_reports'
            ],

            self::PROFESSIONAL => [
                'pos',
                'inventory',
                'advanced_analytics',
                'subdomain_access',
                'team_management',
                'expense_tracker',
                'whatsapp_notifications'
            ],

            self::ENTERPRISE => [
                'pos',
                'inventory',
                'advanced_analytics',
                'custom_domain',
                'team_management',
                'email_notifications',
                'whatsapp_notifications',
                'api_access',
                'dedicated_support',
                'e_commerce_sync',
                'multi_branch'
            ],
        };
    }

    public function hasModule(string $moduleName): bool
    {
        return in_array($moduleName, $this->modules());
    }
}
