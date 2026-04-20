<?php

namespace App\Http\Middleware;

use App\Enums\BusinessType;
use App\Enums\Plan;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->roles,
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                    'shop' => $request->user()->shop ? [
                        'id' => $request->user()->shop->id,
                        'slug' => $request->user()->shop->slug,
                    ] : null,
                ] : null,
            ],
            'business_types' => array_map(fn($type) => [
                'value' => $type->value,
                'label' => $type->label(),
            ], BusinessType::cases()),
            'currency' => $this->getCurrency($request),
            'plans' => array_map(fn($plan) => [
                'value' => $plan->value,
                'label' => $plan->label(),
                'price' => $plan->price($this->getCurrency($request)),
                'modules' => $plan->modules(),
            ], Plan::cases()),
        ];
    }

    private function getCurrency(Request $request): string
    {
        // Simple detection based on locale or query param
        if ($request->has('currency')) {
            return strtoupper($request->query('currency'));
        }

        // Mock BD detection for now - in production you'd use a GeoIP service
        $isBD = str_contains($request->header('Accept-Language', ''), 'bn') ||
                str_contains($request->header('User-Agent', ''), 'Dhaka');

        return $isBD ? 'BDT' : 'USD';
    }
}
