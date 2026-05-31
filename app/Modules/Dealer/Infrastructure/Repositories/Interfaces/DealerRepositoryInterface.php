<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Infrastructure\Repositories\Interfaces;

use App\Modules\Dealer\Domain\Models\Dealer;
use Illuminate\Database\Eloquent\Collection;

interface DealerRepositoryInterface
{
    /**
     * Get all dealers for the current tenant.
     */
    public function getAll(): Collection;

    /**
     * Find a dealer by ID.
     */
    public function find(string $id): ?Dealer;

    /**
     * Find a dealer by UUID.
     */
    public function findByUuid(string $uuid): ?Dealer;

    /**
     * Create a new dealer.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Dealer;

    /**
     * Update an existing dealer.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Dealer $dealer, array $data): bool;

    /**
     * Soft-delete a dealer.
     */
    public function delete(Dealer $dealer): ?bool;

    /**
     * Update the outstanding balance atomically.
     */
    public function updateOutstandingBalance(Dealer $dealer, float $amount): bool;
}
