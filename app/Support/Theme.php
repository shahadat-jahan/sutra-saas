<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Theme configuration and colors for the entire application.
 * Primary color palette extracted from Welcome page design.
 */
class Theme
{
    // Primary Colors from Welcome Design
    public const PRIMARY = '#6366f1'; // Indigo
    public const PRIMARY_DARK = '#4f46e5';
    public const PRIMARY_LIGHT = '#818cf8';

    public const SECONDARY = '#a855f7'; // Purple
    public const SECONDARY_DARK = '#9333ea';
    public const SECONDARY_LIGHT = '#c084fc';

    public const ACCENT = '#ec4899'; // Pink
    public const ACCENT_DARK = '#db2777';
    public const ACCENT_LIGHT = '#f472b6';

    // Background Colors
    public const BG_DARK = '#0f172a'; // slate-950
    public const BG_DARK_SECONDARY = '#1e293b'; // slate-800
    public const BG_LIGHT = '#ffffff';
    public const BG_LIGHT_SECONDARY = '#f1f5f9'; // slate-100

    // Text Colors
    public const TEXT_DARK_PRIMARY = '#ffffff';
    public const TEXT_DARK_SECONDARY = '#cbd5e1'; // slate-300
    public const TEXT_DARK_TERTIARY = '#94a3b8'; // slate-400
    public const TEXT_LIGHT_PRIMARY = '#0f172a'; // slate-950
    public const TEXT_LIGHT_SECONDARY = '#475569'; // slate-600
    public const TEXT_LIGHT_TERTIARY = '#94a3b8'; // slate-400

    // Border Colors
    public const BORDER_DARK = 'rgba(255, 255, 255, 0.1)';
    public const BORDER_LIGHT = 'rgba(0, 0, 0, 0.1)';

    /**
     * Get color palette based on mode
     */
    public static function getPalette(string $mode = 'dark'): array
    {
        if ($mode === 'light') {
            return self::getLightPalette();
        }
        return self::getDarkPalette();
    }

    private static function getDarkPalette(): array
    {
        return [
            'primary' => self::PRIMARY,
            'primary_dark' => self::PRIMARY_DARK,
            'primary_light' => self::PRIMARY_LIGHT,
            'secondary' => self::SECONDARY,
            'secondary_dark' => self::SECONDARY_DARK,
            'secondary_light' => self::SECONDARY_LIGHT,
            'accent' => self::ACCENT,
            'accent_dark' => self::ACCENT_DARK,
            'accent_light' => self::ACCENT_LIGHT,
            'background' => self::BG_DARK,
            'background_secondary' => self::BG_DARK_SECONDARY,
            'text_primary' => self::TEXT_DARK_PRIMARY,
            'text_secondary' => self::TEXT_DARK_SECONDARY,
            'text_tertiary' => self::TEXT_DARK_TERTIARY,
            'border' => self::BORDER_DARK,
        ];
    }

    private static function getLightPalette(): array
    {
        return [
            'primary' => self::PRIMARY,
            'primary_dark' => self::PRIMARY_DARK,
            'primary_light' => self::PRIMARY_LIGHT,
            'secondary' => self::SECONDARY,
            'secondary_dark' => self::SECONDARY_DARK,
            'secondary_light' => self::SECONDARY_LIGHT,
            'accent' => self::ACCENT,
            'accent_dark' => self::ACCENT_DARK,
            'accent_light' => self::ACCENT_LIGHT,
            'background' => self::BG_LIGHT,
            'background_secondary' => self::BG_LIGHT_SECONDARY,
            'text_primary' => self::TEXT_LIGHT_PRIMARY,
            'text_secondary' => self::TEXT_LIGHT_SECONDARY,
            'text_tertiary' => self::TEXT_LIGHT_TERTIARY,
            'border' => self::BORDER_LIGHT,
        ];
    }

    /**
     * Get default admin branding URLs
     */
    public static function getAdminBranding(): array
    {
        return [
            'logo' => '/images/logo.png',
            'banner' => '/images/banner.png',
            'watermark' => '/images/watermark.png',
            'favicon' => '/favicon.ico',
        ];
    }

    /**
     * Get default shop branding URLs
     */
    public static function getShopDefaults(): array
    {
        return [
            'logo' => '/images/logo.png',
            'banner' => '/images/banner.png',
            'watermark' => '/images/watermark.png',
        ];
    }
}
