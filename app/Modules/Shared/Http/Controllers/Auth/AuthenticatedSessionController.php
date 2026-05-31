<?php

declare(strict_types=1);

namespace App\Modules\Shared\Http\Controllers\Auth;

use App\Modules\Shared\Http\Controllers\Controller;
use App\Modules\Shared\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        Log::info('Login attempt started', ['email' => $request->email]);

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        Log::info('Login successful', [
            'user_id' => $user->id,
            'session_id' => $request->session()->getId(),
        ]);

        // If user has a shop, redirect to shop subdomain
        if ($user->shop) {
            Log::info('Redirecting to shop subdomain', ['subdomain' => $user->shop->slug]);

            return Inertia::location(route('dashboard', ['subdomain' => $user->shop->slug]));
        }

        // If no shop (Super Admin), redirect to admin dashboard on main domain
        Log::info('Redirecting to admin dashboard');

        return redirect('/admin/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Inertia::location(config('app.url').'/login');
    }
}
