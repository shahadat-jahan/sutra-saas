<?php

namespace App\Modules\Pos\Application\Services;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Models\Sale;
use App\Modules\Finance\Application\Services\BakirKhataService;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\SaleRepositoryInterface;
use Exception;

/**
 * Service to handle complex Sale processing logic.
 */
class SaleService
{
    public function __construct(
        protected CustomerService $customerService,
        protected BakirKhataService $bakirKhataService,
        protected SaleRepositoryInterface $saleRepository,
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    /**
     * Process a sale with conditional logic for Cash vs Credit vs Partial.
     *
     * @throws Exception
     */
    public function processSale(array $data): Sale
    {
        // Map string to Enum if necessary
        $paymentMethodString = $data['payment_method'];
        if ($paymentMethodString === 'Partial') {
            $paymentMethod = PaymentMethod::CREDIT; // Treat Partial as Credit payment method
            $isPartial = true;
        } else {
            $paymentMethod = $data['payment_method'] instanceof PaymentMethod
                ? $data['payment_method']
                : PaymentMethod::from($paymentMethodString === 'Cash' ? 1 : 4);
            $isPartial = false;
        }

        $totalAmount = (float) $data['total_amount'];
        $customer = null;

        // 1. Handle Credit or Partial Logic (both require customer)
        if ($paymentMethod === PaymentMethod::CREDIT || $isPartial) {
            if (empty($data['customer_id']) && empty($data['customer'])) {
                throw new Exception('A registered or new customer is required for credit or partial sales.');
            }

            // Find or create the customer record
            if (! empty($data['customer_id'])) {
                $customer = $this->customerRepository->find($data['customer_id']);
            } elseif (! empty($data['customer'])) {
                $customer = $this->customerService->findOrCreate($data['customer']);
            }

            // 2. Handle Validation (Check credit_limit for registered customers on credit sales)
            if ($paymentMethod === PaymentMethod::CREDIT && ! $isPartial && ! $this->bakirKhataService->canExtendCredit($customer, $totalAmount)) {
                throw new Exception("Credit limit exceeded. This sale cannot be processed as 'Credit'.");
            }
        }

        // 3. Create the Sale record
        // Requirement: Fast Sale Logic - If 'Cash', customer_id must be null.
        $paidAmount = $data['paid_amount'] ?? ($paymentMethod === PaymentMethod::CASH ? $totalAmount : 0);
        $dueAmount = $totalAmount - $paidAmount;

        $sale = $this->saleRepository->create([
            'shop_id' => $data['shop_id'],
            'user_id' => $data['user_id'],
            'customer_id' => ($paymentMethod === PaymentMethod::CASH) ? null : $customer?->id,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_method' => $paymentMethod,
            'status' => ($dueAmount > 0) ? SaleStatus::CREDIT : SaleStatus::PAID,
            'metadata' => $data['metadata'] ?? null,
        ]);

        // 4. Update Ledger if there's due amount (Credit or Partial)
        if ($dueAmount > 0 && $customer) {
            $this->bakirKhataService->recordCreditSale($customer, $dueAmount);
        }

        // Note: SaleProcessed event is automatically dispatched by the Sale model on 'saved'.

        return $sale;
    }
}
