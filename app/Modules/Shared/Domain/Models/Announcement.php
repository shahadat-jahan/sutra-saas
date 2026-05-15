<?php

declare(strict_types=1);

namespace App\Modules\Shared\Domain\Models;

use App\Enums\ActiveStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory, HasUuid;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'body',
        'metadata',
        'published_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'published_at' => 'datetime',
            'status' => ActiveStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
