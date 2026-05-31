<?php

declare(strict_types=1);

namespace App\Modules\Shared\Application\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Centralized cache service that enforces Redis as the cache driver.
 *
 * All cache operations should be performed through this service to guarantee
 * that Redis is used consistently across the application.
 */
final class CacheService
{
    /**
     * Store an item in the cache for a given number of seconds.
     */
    public function put(string $key, mixed $value, int|float $seconds): void
    {
        Cache::store('redis')->put($key, $value, $seconds);
    }

    /**
     * Retrieve an item from the cache.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::store('redis')->get($key, $default);
    }

    /**
     * Retrieve an item or store the default value using a callback.
     */
    public function remember(string $key, int|float $seconds, callable $callback): mixed
    {
        return Cache::store('redis')->remember($key, $seconds, $callback);
    }

    /**
     * Remove an item from the cache.
     */
    public function forget(string $key): bool
    {
        return Cache::store('redis')->forget($key);
    }

    /**
     * Flush the entire Redis cache.
     */
    public function flush(): void
    {
        Cache::store('redis')->clear();
    }
}
