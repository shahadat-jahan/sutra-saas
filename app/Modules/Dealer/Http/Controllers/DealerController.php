<?php

declare(strict_types=1);

namespace App\Modules\Dealer\Http\Controllers;

use App\Modules\Dealer\Application\DTOs\DealerDTO;
use App\Modules\Dealer\Application\Services\DealerService;
use App\Modules\Dealer\Domain\Models\Dealer;
use App\Modules\Dealer\Http\Requests\StoreDealerRequest;
use App\Modules\Dealer\Infrastructure\Repositories\Interfaces\DealerRepositoryInterface;
use App\Modules\Shared\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DealerController extends Controller
{
    public function __construct(
        private readonly DealerService $dealerService,
        private readonly DealerRepositoryInterface $dealerRepository
    ) {}

    /**
     * Store a newly created dealer in storage.
     */
    public function store(StoreDealerRequest $request): JsonResponse
    {
        $dto = DealerDTO::fromArray($request->validated());

        $dealer = $this->dealerService->createDealer($dto);

        return response()->json([
            'success' => true,
            'message' => 'Dealer created successfully',
            'data' => [
                'dealer' => [
                    'id' => $dealer->id,
                    'uuid' => $dealer->uuid,
                    'businessName' => $dealer->business_name,
                    'contactPerson' => $dealer->contact_person,
                    'creditLimit' => $dealer->credit_limit,
                    'outstandingBalance' => $dealer->outstanding_balance,
                    'tier' => $dealer->tier,
                ],
            ],
        ], 201);
    }

    /**
     * Get a specific dealer.
     */
    public function show(string $uuid): JsonResponse
    {
        $dealer = $this->dealerRepository->findByUuid($uuid);

        if (! $dealer) {
            return response()->json([
                'success' => false,
                'message' => 'Dealer not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Dealer retrieved successfully',
            'data' => [
                'dealer' => [
                    'id' => $dealer->id,
                    'uuid' => $dealer->uuid,
                    'businessName' => $dealer->business_name,
                    'contactPerson' => $dealer->contact_person,
                    'creditLimit' => $dealer->credit_limit,
                    'outstandingBalance' => $dealer->outstanding_balance,
                    'tier' => $dealer->tier,
                ],
            ],
        ]);
    }
}
