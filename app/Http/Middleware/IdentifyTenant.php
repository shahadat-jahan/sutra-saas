<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class IdentifyTenant
{
    public function __construct(protected TenantManager $tenantManager)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appDomain = config('app.domain', 'localhost');
        $hostWithoutPort = preg_replace('/:\d+$/', '', $host);

        if ($hostWithoutPort !== $appDomain && Str::endsWith($hostWithoutPort, '.' . $appDomain)) {
            // It's a subdomain 
            $subdomain = Str::before($hostWithoutPort, '.' . $appDomain);

            $shop = Shop::where('slug', $subdomain)->where('status', 1)->first();

            if (! $shop) {
                abort(404, 'Shop not found or inactive.');
            }

            // Register tenant in the manager so other parts of the app can access it
            $this->tenantManager->setTenant($shop);

            // Set Team Context for Spatie Permissions
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($shop->id);

            // Cross-tenant protection: Ensure logged in user belongs to this shop
            // We allow Super Admins (shop_id === null) to access any shop
            if (Auth::check() && Auth::user()->shop_id !== null && Auth::user()->shop_id !== $shop->id) {
                \Illuminate\Support\Facades\Log::warning('Cross-tenant protection triggered: Logging out user', [
                    'user_id' => Auth::id(),
                    'user_shop_id' => Auth::user()->shop_id,
                    'request_shop_id' => $shop->id,
                    'host' => $host
                ]);
                
                Auth::logout();
                
                if (!$request->routeIs('login')) {
                    return redirect()->route('login')->with('error', 'You do not have access to this shop.');
                }
            }
        }

        return $next($request);
    }
}
