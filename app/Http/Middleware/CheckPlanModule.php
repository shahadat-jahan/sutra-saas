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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        // If not logged in or doesn't have a shop, let them pass (auth middleware should handle this)
        if (! $user || ! $user->shop) {
            return $next($request);
        }

        $enabledModules = $user->shop->enabled_modules ?? [];

        // Module access is driven by per-shop module selection.
        if (! in_array($module, $enabledModules, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Your shop does not have the '{$module}' module enabled.",
                ], 403);
            }

            return redirect()->route('dashboard', ['subdomain' => $user->shop->slug])
                ->with('error', "Your shop does not have the '{$module}' module enabled.");
        }

        return $next($request);
    }
}
