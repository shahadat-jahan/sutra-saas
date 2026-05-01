<?php

namespace App\Traits;

/**
 * Trait HasDynamicAttributes
 *
 * Allows magic access to attributes stored in a JSONB column.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasDynamicAttributes
{
    /**
     * Get the dynamic attributes column name.
     * This must be implemented by the model.
     */
    abstract protected function getDynamicAttributesColumn(): string;

    /**
     * Magic __get to fetch data from dynamic JSONB columns.
     */
    public function __get($key)
    {
        // 1. Attempt to get the standard attribute or relationship using the Model's logic
        $value = parent::__get($key);

        // 2. If the value is not null, or it's a known attribute/relationship, return it
        if ($value !== null || $this->isStandardAttribute($key)) {
            return $value;
        }

        // 3. Fallback to dynamic JSONB column if standard attribute is missing
        $column = $this->getDynamicAttributesColumn();
        $dynamicData = parent::__get($column);

        if (is_array($dynamicData) && array_key_exists($key, $dynamicData)) {
            return $dynamicData[$key];
        }

        return null;
    }

    /**
     * Determine if a key is a standard attribute, relationship, or mutator.
     */
    protected function isStandardAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes) ||
               (method_exists($this, 'getCasts') && array_key_exists($key, $this->getCasts())) ||
               method_exists($this, \Illuminate\Support\Str::camel($key));
    }
}
