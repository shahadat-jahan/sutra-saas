<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products for a shop.
     *
     * @param string $shopId
     * @return Collection
     */
    public function getByShop(string $shopId): Collection;

    /**
     * Find a product by ID.
     *
     * @param string $id
     * @return Product|null
     */
    public function find(string $id): ?Product;

    /**
     * Create a new product.
     *
     * @param array<string, mixed> $data
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * Update a product.
     *
     * @param Product $product
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(Product $product, array $data): bool;

    /**
     * Delete a product.
     *
     * @param Product $product
     * @return bool|null
     */
    public function delete(Product $product): ?bool;
}

