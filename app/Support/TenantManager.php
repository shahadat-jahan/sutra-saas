<?php

namespace App\Support;

use App\Models\Shop;

class TenantManager
{
    protected ?Shop $tenant = null;

    /**
     * Get the current active tenant (Shop) from the subdomain.
     */
    public function getTenant(): ?Shop
    {
        return $this->tenant;
    }

    /**
     * Set the current active tenant (Shop).
     */
    public function setTenant(?Shop $shop): void
    {
        $this->tenant = $shop;
    }

    /**
     * Get the ID of the current active tenant (Shop).
     */
    public function getTenantId(): ?int
    {
        return $this->tenant?->id;
    }
}
