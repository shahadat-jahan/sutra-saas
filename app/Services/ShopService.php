<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Shop;
use App\Repositories\Interfaces\ShopRepositoryInterface;

final class ShopService
{
    public function __construct(
        private readonly ShopRepositoryInterface $shopRepository
    ) {}

    /**
     * Update a shop.
     *
     * @param Shop $shop
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(Shop $shop, array $data): bool
    {
        return $this->shopRepository->update($shop, $data);
    }

    /**
     * Get shop count.
     *
     * @return int
     */
    public function getCount(): int
    {
        return Shop::count();
    }

    /**
     * Get count of active shops.
     *
     * @return int
     */
    public function getActiveCount(): int
    {
        return Shop::where('status', 1)->count();
    }
}

