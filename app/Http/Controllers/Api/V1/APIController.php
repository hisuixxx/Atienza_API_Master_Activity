<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\APIResponse;

class APIController extends Controller
{
    // Gives every controller that extends this class the ok(), success(), error() helpers.
    use APIResponse;

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
}

