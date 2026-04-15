<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

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
        }

        return $next($request);
    }
}
