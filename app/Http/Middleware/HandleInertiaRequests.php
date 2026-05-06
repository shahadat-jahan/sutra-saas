<?php

namespace App\Http\Middleware;

use App\Enums\BusinessType;
use App\Models\Announcement;
use App\Models\Shop;
use App\Support\Theme;
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
        $themeMode = (string) $request->session()->get('theme_mode', 'dark');

        $query = Announcement::query();
        $query->whereNotNull('published_at');
        $query->orderBy('published_at', 'desc');
        $query->limit(3);
        $announcements = $query->get()->map(function ($a) {
            return [
                'uuid' => $a->uuid,
                'title' => $a->title,
                'body' => $a->body,
                'published_at' => $a->published_at ? $a->published_at->diffForHumans() : null,
            ];
        });

        return [
            ...parent::share($request),
            'appName' => (string) config('app.name', 'Sutra'),
            'appDomain' => (string) config('app.domain', parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost'),
            'themeMode' => $themeMode,
            'themePalette' => Theme::getPalette($themeMode),
            'adminBranding' => Theme::getAdminBranding(),
            'shopDefaults' => Theme::getShopDefaults(),
            'announcements' => $announcements,
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
            'business_types' => array_map(fn ($type) => [
                'value' => $type->value,
                'label' => $type->label(),
            ], BusinessType::cases()),
            'module_catalog' => Shop::moduleCatalog(),
            'currency' => $this->getCurrency($request),
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
