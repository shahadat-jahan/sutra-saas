<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class ProductRepository implements ProductRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getByShop(string $shopId): Collection
    {
        return Product::where('shop_id', $shopId)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }
}
