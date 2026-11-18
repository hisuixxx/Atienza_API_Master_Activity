<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\V1\UserResource;
use App\Http\Filters\V1\AuthorFilter;

class AuthorsController extends APIController
{
    /**
     * GET /api/v1/authors
     * Lists only users who own at least one booking, 15 per page.
     */
    public function index(AuthorFilter $filters)
    {
    //     //
    //     // ?include=products: load each author's products in one extra query
    //     if ($this->include('products')) {
    //         return UserResource::collection(User::has('products')->with('products')->paginate());
    //     }

    //     return UserResource::collection(User::has('bookings')->paginate());
        
        return UserResource::collection(
            User::has('bookings')->filter($filters)->paginate()
        );  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author)
    {
        // ?include=products: load this author's products
        if ($this->include('products')) {
            return new UserResource($author->load('products'));
        }

        return new UserResource($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
