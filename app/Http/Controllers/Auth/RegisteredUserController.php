<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TenantRegisterRequest;
use App\Services\TenantRegistrationService;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly TenantRegistrationService $tenantRegistrationService
    ) {}

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(TenantRegisterRequest $request): SymfonyResponse
    {
        $user = $this->tenantRegistrationService->registerTenant(
            [
                'name' => $request->shop_name,
                'business_type' => $request->business_type,
                'plan' => $request->plan,
            ],
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]
        );

        // Redirect to new subdomain
        $appDomain = config('app.domain', 'localhost');
        $port = $request->getPort() == 8000 ? ':8000' : '';
        $subdomainUrl = $request->getScheme()
            . '://'
            . $user->shop->slug
            . '.'
            . $appDomain
            . $port
            . '/dashboard';

        return Inertia::location($subdomainUrl);
    }
}
