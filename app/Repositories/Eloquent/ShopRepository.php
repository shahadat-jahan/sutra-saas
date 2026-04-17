<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Shop;
use App\Repositories\Interfaces\ShopRepositoryInterface;

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
