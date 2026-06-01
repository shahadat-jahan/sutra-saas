<?php

declare(strict_types=1);

namespace App\Modules\Shared\Application\Services;

use App\Events\ShopDeletedEvent;
use App\Events\ShopUpdatedEvent;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Infrastructure\Repositories\Interfaces\ShopRepositoryInterface;

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
        $result = $this->shopRepository->update($shop, $data);

        // Fire the event only once at the service layer
        if ($result) {
            event(new ShopUpdatedEvent($shop, $data));
        }

        return $result;
    }

    /**
     * Delete a shop and trigger cleanup event.
     */
    public function delete(Shop $shop): bool
    {
        // Fire the event before deletion so listener can notify owner
        event(new ShopDeletedEvent($shop));

        // The event listener handles user/role deletion
        // Only delete the shop record itself
        return (bool) $shop->delete();
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
