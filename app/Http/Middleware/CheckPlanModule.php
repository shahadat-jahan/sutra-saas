<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanModule
{
    /**
     * Handle an incoming request.
     *
     * @param Request                      $request
     * @param Closure(Request): (Response) $next
     * @param string                       $module
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        // If not logged in or doesn't have a shop, let them pass (auth middleware should handle this)
        if (!$user || !$user->shop) {
            return $next($request);
        }

        // Check if the plan associated with the shop has the required module
        if (!$user->shop->plan->hasModule($module)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Your current plan does not include the '{$module}' module. Please upgrade to access this feature.",
                ], 403);
            }

            return redirect()->route('dashboard', ['subdomain' => $user->shop->slug])
                ->with('error', "Your current plan does not include the '{$module}' module. Please upgrade to access this feature.");
        }

        return $next($request);
    }
}
