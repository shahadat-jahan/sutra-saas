<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot function from Laravel to handle auto-generation of UUID.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model. (Optional: To use UUID in URLs instead of ID)
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}