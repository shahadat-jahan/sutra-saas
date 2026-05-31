<?php

namespace App\Modules\Shared\Http\Controllers\Auth;

use App\Modules\Shared\Http\Controllers\Controller;
use App\Support\Dashboard;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(Dashboard::urlFor($request->user(), ['verified' => 1]));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(Dashboard::urlFor($request->user(), ['verified' => 1]));
    }
}
