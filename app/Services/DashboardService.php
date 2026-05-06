<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Models\Announcement;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class DashboardService
{
    /**
     * Get admin dashboard statistics.
     *
     * @return array<string, mixed>
     */
    public function getAdminStats(): array
    {
        $now = now();
        $currentPeriodStart = $now->copy()->subDays(30);
        $previousPeriodStart = $now->copy()->subDays(60);

        $newUsersCurrent = User::where('created_at', '>=', $currentPeriodStart)->count();
        $newUsersPrevious = User::whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])->count();
        $newShopsCurrent = Shop::where('created_at', '>=', $currentPeriodStart)->count();
        $newShopsPrevious = Shop::whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])->count();

        return [
            'total_shops' => Shop::count(),
            'total_users' => User::count(),
            'active_shops' => Shop::where('status', ActiveStatus::ACTIVE->value)->count(),
            'new_users_30d' => $newUsersCurrent,
            'new_shops_30d' => $newShopsCurrent,
            'users_change_pct_30d' => $this->percentChange($newUsersCurrent, $newUsersPrevious),
            'shops_change_pct_30d' => $this->percentChange($newShopsCurrent, $newShopsPrevious),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminDashboardData(): array
    {
        return [
            'stats' => $this->getAdminStats(),
            'charts' => [
                'activity_6m' => $this->monthlyCounts(User::query(), 6),
                'activity_12m' => $this->monthlyCounts(User::query(), 12),
                'sectors' => $this->shopSectorCounts(),
            ],
            'announcements' => Announcement::query()
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->take(5)
                ->get()
                ->map(fn (Announcement $announcement) => [
                    'uuid' => $announcement->uuid,
                    'title' => $announcement->title,
                    'published_at' => $announcement->published_at?->toDateTimeString(),
                ])
                ->all(),
        ];
    }

    /**
     * @param  Builder<Model>  $query
     * @return array<int, array{name: string, value: int}>
     */
    private function monthlyCounts($query, int $months): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $buckets = [];
        $index = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $key = $month->format('Y-m');
            $index[$key] = count($buckets);
            $buckets[] = [
                'name' => $month->format('M'),
                'value' => 0,
            ];
        }

        $rows = $query
            ->where('created_at', '>=', $start)
            ->get(['created_at']);

        foreach ($rows as $row) {
            $createdAt = $row->created_at instanceof Carbon ? $row->created_at : Carbon::parse($row->created_at);
            $key = $createdAt->copy()->startOfMonth()->format('Y-m');
            if (array_key_exists($key, $index)) {
                $buckets[$index[$key]]['value']++;
            }
        }

        return $buckets;
    }

    /**
     * @return array<int, array{name: string, value: int}>
     */
    private function shopSectorCounts(): array
    {
        $counts = [];
        foreach (BusinessType::cases() as $type) {
            $counts[$type->value] = 0;
        }

        $rawRows = DB::table('shops')->select(['business_type'])->get();
        foreach ($rawRows as $row) {
            $raw = $row->business_type;

            if (is_int($raw) || (is_string($raw) && ctype_digit($raw))) {
                $value = (int) $raw;
            } elseif (is_string($raw)) {
                $normalized = strtolower(trim($raw));
                $value = match ($normalized) {
                    'retail', 'retail shop' => BusinessType::RETAIL->value,
                    'pharmacy' => BusinessType::PHARMACY->value,
                    default => null,
                };
            } else {
                $value = null;
            }

            if ($value !== null && array_key_exists($value, $counts)) {
                $counts[$value]++;
            }
        }

        $result = [];
        foreach (BusinessType::cases() as $type) {
            $result[] = [
                'name' => $type->label(),
                'value' => $counts[$type->value] ?? 0,
            ];
        }

        usort($result, fn (array $a, array $b) => $b['value'] <=> $a['value']);

        return $result;
    }

    private function percentChange(int $current, int $previous): int
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }
}
