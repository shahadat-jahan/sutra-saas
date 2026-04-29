<?php

namespace App\Modules\Pos\Application\Services;

use App\Models\Sale;
use App\Repositories\Interfaces\SaleRepositoryInterface;
use App\Modules\Finance\Application\Services\BakirKhataService;
use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use Exception;

/**
 * Service to handle complex Sale processing logic.
 */
class SaleService
{
    public function __construct(
        protected CustomerService $customerService,
        protected BakirKhataService $bakirKhataService,
        protected SaleRepositoryInterface $saleRepository
    ) {}

    /**
     * Process a sale with conditional logic for Cash vs Credit.
     * 
     * @param array $data
     * @return Sale
     * @throws Exception
     */
    public function processSale(array $data): Sale
    {
        // Map string to Enum if necessary
        $paymentMethod = $data['payment_method'] instanceof PaymentMethod 
            ? $data['payment_method'] 
            : PaymentMethod::from($data['payment_method'] === 'Cash' ? 1 : 4); 

        $totalAmount = (float) $data['total_amount'];
        $customer = null;

        // 1. Handle Baki (Credit) Logic
        if ($paymentMethod === PaymentMethod::CREDIT) {
            if (empty($data['customer'])) {
                throw new Exception("A registered or new customer is required for credit sales.");
            }

            // Find or create the customer record
            $customer = $this->customerService->findOrCreate($data['customer']);

            // 2. Handle Validation (Check credit_limit for registered customers)
            if (!$this->bakirKhataService->canExtendCredit($customer, $totalAmount)) {
                throw new Exception("Credit limit exceeded. This sale cannot be processed as 'Credit'.");
            }
        }

        // 3. Create the Sale record
        // Requirement: Fast Sale Logic - If 'Cash', customer_id must be null.
        $sale = $this->saleRepository->create([
            'shop_id' => $data['shop_id'],
            'user_id' => $data['user_id'],
            'customer_id' => ($paymentMethod === PaymentMethod::CASH) ? null : $customer?->id,
            'total_amount' => $totalAmount,
            'paid_amount' => ($paymentMethod === PaymentMethod::CASH) ? $totalAmount : ($data['paid_amount'] ?? 0),
            'due_amount' => ($paymentMethod === PaymentMethod::CREDIT) ? ($totalAmount - ($data['paid_amount'] ?? 0)) : 0,
            'payment_method' => $paymentMethod,
            'status' => ($paymentMethod === PaymentMethod::CASH) ? SaleStatus::PAID : SaleStatus::CREDIT,
            'metadata' => $data['metadata'] ?? null,
        ]);

        // 4. Update Ledger if it's a Credit sale
        if ($paymentMethod === PaymentMethod::CREDIT && $customer) {
            $this->bakirKhataService->recordCreditSale($customer, (float) $sale->due_amount);
        }

        // Note: SaleProcessed event is automatically dispatched by the Sale model on 'saved'.

        return $sale;
    }
}
