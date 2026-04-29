<?php

namespace App\Repositories\Eloquent;

use App\Models\Sale;
use App\Repositories\Interfaces\SaleRepositoryInterface;

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
