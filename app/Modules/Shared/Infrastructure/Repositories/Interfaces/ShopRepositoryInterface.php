<?php

declare(strict_types=1);

namespace App\Modules\Shared\Infrastructure\Repositories\Interfaces;

use App\Modules\Shared\Domain\Models\Shop;

interface ShopRepositoryInterface
{
    /**
     * Create a new shop.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Shop;

    /**
     * Update a shop.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Shop $shop, array $data): bool;
}
