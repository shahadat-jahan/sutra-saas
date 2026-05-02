<?php

declare(strict_types=1);

namespace App\Modules\Finance\Application\Services;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Support\TenantManager;
use InvalidArgumentException;

/**
 * Service to handle Bakir Khata (Credit) logic.
 * Follows SOLID principles by focusing on credit validation.
 */
class BakirKhataService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected TenantManager $tenantManager
    ) {}

    /**
     * Validate if the customer can afford the sale based on their credit limit.
     */
    public function canExtendCredit(Customer $customer, float $saleAmount): bool
    {
        $this->ensureModuleEnabled();

        // Logic: sale_amount + current_balance > credit_limit
        // If the above is true, it fails validation.
        $totalPotentialDebt = (float) $saleAmount + (float) $customer->current_balance;

        return $totalPotentialDebt <= (float) $customer->credit_limit;
    }

    /**
     * Record a credit sale and update the customer's balance.
     */
    public function recordCreditSale(Customer $customer, float $amount): void
    {
        $this->ensureModuleEnabled();

        // Update customer balance via repository
        $this->customerRepository->incrementBalance($customer, $amount);

        // Optionally: Create a TransactionLog entry here if needed
    }

    /**
     * Ensure the pharma module is enabled for this shop.
     */
    private function ensureModuleEnabled(): void
    {
        if (! $this->tenantManager->isModuleEnabled('pharma')) {
            throw new InvalidArgumentException('Pharma module is not enabled for this shop.');
        }
    }
}
