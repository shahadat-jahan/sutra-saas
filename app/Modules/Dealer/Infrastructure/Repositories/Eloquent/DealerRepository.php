<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Infrastructure\Repositories\Eloquent;

use App\Modules\Dealer\Domain\Models\Dealer;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class DealerRepository implements DealerRepositoryInterface
{
    public function getAll(): Collection
    {
        return Dealer::orderBy('business_name')->get();
    }

    public function find(string $id): ?Dealer
    {
        return Dealer::find($id);
    }

    public function findByUuid(string $uuid): ?Dealer
    {
        return Dealer::where('uuid', $uuid)->first();
    }

    public function create(array $data): Dealer
    {
        return Dealer::create($data);
    }

    public function update(Dealer $dealer, array $data): bool
    {
        return $dealer->update($data);
    }

    public function delete(Dealer $dealer): ?bool
    {
        return $dealer->delete();
    }

    public function updateOutstandingBalance(Dealer $dealer, float $amount): bool
    {
        // Use an atomic update to prevent race conditions
        $affected = DB::table('dealers')
            ->where('id', $dealer->id)
            ->update([
                'outstanding_balance' => DB::raw("outstanding_balance + {$amount}"),
            ]);

        if ($affected) {
            // Refresh the model to get the new balance
            $dealer->refresh();

            return true;
        }

        return false;
    }
}
