<?php

namespace App\Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Modules\Pos\Application\Services\SaleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    /**
     * Display the POS interface.
     */
    public function index(): Response
    {
        return Inertia::render('Pos/Index', [
            'products' => Product::query()->orderByDesc('created_at')->get(),
            'customers' => Customer::active()->orderByDesc('created_at')->get(),
        ]);
    }

    /**
     * Handle the sale submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => 'required|array',
            'customer_id' => 'nullable',
            'payment_method' => 'required|string',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
            'customer' => 'nullable|array', // For new customer creation
        ]);

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
