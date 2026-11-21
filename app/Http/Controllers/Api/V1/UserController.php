<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class UserController extends APIController
{
    /**
     * GET /api/v1/users
     * Lists every user (not only authors), with the filters and sorting from the URL.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::filter($filters)->paginate()
        );
    }

    /**
     * POST /api/v1/users
     * Creates a user (needs the user:create ability).
     */
    public function store(StoreUserRequest $request)
    {
        if ($this->isAble('store', User::class)) {
            return new UserResource(User::create($request->mappedAttributes()));
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * GET /api/v1/users/{user}
     * Shows one user. ?include=bookings also loads the user's bookings.
     */
    public function show(User $user)
    {
        if ($this->include('bookings')) {
            return new UserResource($user->load('bookings'));
        }

        return new UserResource($user);
    }
    

    /**
     * PATCH /api/v1/users/{user}
     * Updates only the fields that were sent (needs user:update).
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($this->isAble('update', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * PUT /api/v1/users/{user}
     * Replaces the whole user: every field is required (needs user:replace).
     */
    public function replace(ReplaceUserRequest $request, User $user)
    {
        if ($this->isAble('replace', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * DELETE /api/v1/users/{user}
     * Deletes the user (needs user:delete).
     */
    public function destroy(User $user)
    {
        if ($this->isAble('delete', $user)) {
            $user->delete();

            return $this->ok('User successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }
}
