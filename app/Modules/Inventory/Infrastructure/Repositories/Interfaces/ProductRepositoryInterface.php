<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Infrastructure\Repositories\Interfaces;

use App\Modules\Inventory\Domain\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products for a shop.
     */
    public function getByShop(string $shopId): Collection;

    /**
     * Get optimized products for POS display.
     */
    public function getPosProducts(): Collection;

    /**
     * Find a product by ID.
     */
    public function find(string $id): ?Product;

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product;

    /**
     * Update a product.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): bool;

    /**
     * Delete a product.
     */
    public function delete(Product $product): ?bool;
}
