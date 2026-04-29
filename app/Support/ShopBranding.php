<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Shop;

/**
 * Shop Branding Helper
 * Provides branding assets for shops with fallback to defaults.
 */
class ShopBranding
{
    private Shop $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Get shop logo with fallback to default
     */
    public function getLogo(): string
    {
        if (!empty($this->shop->logo_path)) {
            return storage_path("app/public/{$this->shop->logo_path}");
        }
        return Theme::getShopDefaults()['logo'];
    }

    /**
     * Get shop banner with fallback to default
     */
    public function getBanner(): string
    {
        if (!empty($this->shop->banner_path) && $this->shop->banner_path !== null) {
            return storage_path("app/public/{$this->shop->banner_path}");
        }
        return Theme::getShopDefaults()['banner'];
    }

    /**
     * Get shop watermark with fallback to default
     */
    public function getWatermark(): string
    {
        if (!empty($this->shop->watermark_path) && $this->shop->watermark_path !== null) {
            return storage_path("app/public/{$this->shop->watermark_path}");
        }
        return Theme::getShopDefaults()['watermark'];
    }

    /**
     * Get all branding assets
     */
    public function getAll(): array
    {
        return [
            'logo' => $this->getLogo(),
            'banner' => $this->getBanner(),
            'watermark' => $this->getWatermark(),
        ];
    }

    /**
     * Get branding URLs for JSON response
     */
    public function toArray(): array
    {
        return [
            'logo' => asset(!empty($this->shop->logo_path) ? "storage/{$this->shop->logo_path}" : Theme::getShopDefaults()['logo']),
            'banner' => asset(!empty($this->shop->banner_path) ? "storage/{$this->shop->banner_path}" : Theme::getShopDefaults()['banner']),
            'watermark' => asset(!empty($this->shop->watermark_path) ? "storage/{$this->shop->watermark_path}" : Theme::getShopDefaults()['watermark']),
        ];
    }
}

