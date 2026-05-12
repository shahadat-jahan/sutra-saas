<?php

namespace App\Modules\Sales\Infrastructure\Repositories\Eloquent;

use App\Modules\Sales\Domain\Models\Sale;
use App\Modules\Sales\Infrastructure\Repositories\Interfaces\SaleRepositoryInterface;

class SaleRepository implements SaleRepositoryInterface
{
    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    public function find(int|string $id): ?Sale
    {
        return Sale::find($id);
    }
}
