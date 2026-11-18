<?php

namespace App\Http\Filters\V1;

class QueryFilter
{
    /**
     * Create a new class instance.
     */

    // The Eloquent query being built (set in apply())
    protected Builder $builder;

    // The incoming request, which holds the query-string parameters
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Loop over the query parameters and call the matching method (if it exists).
     * Parameters without a method are ignored.
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            // Skip "apply" and names starting with __ so clients cannot call internal methods
            if ($key !== 'apply' && ! str_starts_with($key, '__') && method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }
}
