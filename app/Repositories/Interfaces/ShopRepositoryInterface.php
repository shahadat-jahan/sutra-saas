<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Shop;

interface ShopRepositoryInterface
{
    /**
     * Create a new shop.
     *
     * @param array<string, mixed> $data
     * @return Shop
     */
    public function create(array $data): Shop;

    /**
     * Update a shop.
     *
     * @param Shop $shop
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(Shop $shop, array $data): bool;
}
