<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\APIResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class APIController extends Controller
{
    // Gives every controller that extends this class the ok(), success(), error() helpers.
    use ApiResponses, AuthorizesRequests;


    /**
     * Returns true when the client asked for a relationship with ?include=...
     * Example: /products?include=author -> include('author') is true.
     */
    public function include(string $relationship): bool
    {
        // Read the "include" query parameter (null when it is not in the URL)
        $param = request()->get('include');

        if (! isset($param)) {
            return false;
        }

        // ?include=author,products -> ['author', 'products']
        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    /**
     * Asks the policy whether the logged-in user may do $ability on $target.
     * Returns true or false instead of throwing an exception.
     *
     * Example: isAble('update', $booking) calls BookingPolicy::update()
     */
    public function isAble(string $ability, mixed $target): bool
    {
        try {
            // authorize() throws AuthorizationException when the policy says no
            $this->authorize($ability, $target);

            return true;
        } catch (AuthorizationException $e) {
            return false;
        }
    }
}

