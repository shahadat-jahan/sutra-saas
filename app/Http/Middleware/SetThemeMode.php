<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Theme;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetThemeMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $themeMode = session('theme_mode', 'dark');

        // Share theme data with all views
        view()->share([
            'themeMode' => $themeMode,
            'themePalette' => Theme::getPalette($themeMode),
            'adminBranding' => Theme::getAdminBranding(),
            'shopDefaults' => Theme::getShopDefaults(),
        ]);

        return $next($request);
    }
}
