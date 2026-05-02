<?php

namespace App\Modules\Inventory\Application\Services;

use App\Enums\BusinessType;
use App\Models\Product;
use App\Models\Shop;

/**
 * Service to handle DGDA (Pharma) synchronization.
 * Updates dynamic attributes for pharmacy-specific logic.
 */
class PharmaSyncService
{
    /**
     * Synchronize product attributes with DGDA standards.
     */
    public function syncProductData(Product $product, Shop $shop): void
    {
        // Only trigger if the business type is Pharmacy
        if ($shop->business_type === BusinessType::PHARMACY) {
            $attributes = $product->attributes ?? [];

            // Add DGDA specific metadata to the JSONB attributes column
            $attributes['dgda_synced_at'] = now()->toDateTimeString();
            $attributes['requires_prescription'] = $attributes['requires_prescription'] ?? false;

            $product->update([
                'attributes' => $attributes,
            ]);
        }
    }
}
