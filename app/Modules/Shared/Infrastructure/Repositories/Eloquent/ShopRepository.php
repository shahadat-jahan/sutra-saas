<?php

declare(strict_types=1);

namespace App\Modules\Shared\Infrastructure\Repositories\Eloquent;

use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Infrastructure\Repositories\Interfaces\ShopRepositoryInterface;

final class ShopRepository implements ShopRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $data): Shop
    {
        return Shop::create([
            'name' => $data['name'],
            'business_type' => $data['business_type'] ?? 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Shop $shop, array $data): bool
    {
        return $shop->update($data);
    }
}
