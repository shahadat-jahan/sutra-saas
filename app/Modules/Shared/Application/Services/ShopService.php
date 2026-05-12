<?php

declare(strict_types=1);

namespace App\Modules\Shared\Application\Services;

use App\Modules\Shared\Domain\Models\Shop;
use App\Repositories\Interfaces\ShopRepositoryInterface;

final class ShopService
{
    public function __construct(
        private readonly ShopRepositoryInterface $shopRepository
    ) {}

    /**
     * Update a shop.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Shop $shop, array $data): bool
    {
        return $this->shopRepository->update($shop, $data);
    }

    /**
     * Get shop count.
     */
    public function getCount(): int
    {
        return Shop::count();
    }

    /**
     * Get count of active shops.
     */
    public function getActiveCount(): int
    {
        return Shop::where('status', 1)->count();
    }
}
