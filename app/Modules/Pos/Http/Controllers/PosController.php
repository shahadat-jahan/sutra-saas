<?php

namespace App\Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Http\Requests\Pos\StoreSaleRequest;
use App\Modules\Pos\Application\Services\SaleService;
use App\Support\TenantManager;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
        protected ProductRepositoryInterface $productRepository,
        protected CustomerRepositoryInterface $customerRepository,
        protected TenantManager $tenantManager
    ) {}

    /**
     * Display the POS interface.
     */
    public function index(): Response
    {
        return Inertia::render('Pos/Index', [
            'products' => $this->productRepository->getPosProducts(),
            'customers' => $this->customerRepository->getActiveCustomers(),
            'enabledModules' => $this->tenantManager->getTenant()?->enabled_modules ?? [],
        ]);
    }

    /**
     * Handle the sale submission.
     */
    public function store(StoreSaleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['shop_id'] = Auth::user()->shop_id;
        $data['user_id'] = Auth::id();

        try {
            $this->saleService->processSale($data);
            return back()->with('success', 'Sale completed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
