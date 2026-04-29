<?php

namespace App\Modules\Finance\Application\Services;

use App\Models\Customer;

/**
 * Service to handle Bakir Khata (Credit) logic.
 * Follows SOLID principles by focusing on credit validation.
 */
class BakirKhataService
{
    /**
     * Validate if the customer can afford the sale based on their credit limit.
     * 
     * @param Customer $customer
     * @param float $saleAmount
     * @return bool
     */
    public function canExtendCredit(Customer $customer, float $saleAmount): bool
    {
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
        // Update customer balance (current_balance increases with debt)
        $customer->increment('current_balance', $amount);

        // Optionally: Create a TransactionLog entry here if needed
    }
}
