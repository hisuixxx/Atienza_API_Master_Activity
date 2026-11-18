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

    // Columns the client may sort by. Child classes fill this in.
    protected array $sortable = [];


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

    /**
     * ?sort=name             ascending
     * ?sort=-createdAt       descending (leading minus)
     * ?sort=status,name      several columns, in order
     */
    protected function sort($value): void
    {
        $sortAttributes = explode(',', $value);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            // A leading minus means descending order
            if (str_starts_with($sortAttribute, '-')) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }

            // Ignore anything that is not on the whitelist
            if (! in_array($sortAttribute, $this->sortable, true)
                && ! array_key_exists($sortAttribute, $this->sortable)) {
                continue;
            }

            // Translate the public name to the real column name
            $columnName = $this->sortable[$sortAttribute] ?? $sortAttribute;

            $this->builder->orderBy($columnName, $direction);
        }  
    }

}
