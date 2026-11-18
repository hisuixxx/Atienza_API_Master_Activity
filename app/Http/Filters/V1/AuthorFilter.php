<?php

namespace App\Http\Filters\V1;

class AuthorFilter extends QueryFilter
{
   
    // The columns clients may sort users by
    protected array $sortable = [
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * ?id=1,2,3  (one or several IDs)
     */
    public function id($value)
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    /**
     * ?name=*john*  (wildcard search)
     */
    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('name', 'like', $likeStr);
    }

    /**
     * ?email=*@example.com  (wildcard search)
     */
    public function email($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('email', 'like', $likeStr);
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

    /**
     * ?include=bookings loads each user's bookings (allowed relationships only)
     */
    public function include($value)
    {
        $allowed = array_intersect(explode(',', $value), ['bookings']);

        return $this->builder->with($allowed);
    
    }
}
