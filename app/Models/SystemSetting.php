<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * @param  mixed  $default
     * @return mixed
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::query()
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function putArray(string $key, array $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
