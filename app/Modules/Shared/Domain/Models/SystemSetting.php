<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Models;

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

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return self::query()
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public static function putArray(string $key, array $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
