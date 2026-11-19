<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Permissions\V1\Abilities;

class StoreBookingRequest extends BaseBookingRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *Validation rules for creating a booking.
     */
    public function rules(): array
    {
       
        // Where the user id comes from: the body on /bookings,
        // or the URL (merged into "user" below) on /users/{user}/bookings
        $userIdAttr = $this->routeIs('bookings.store')
            ? 'data.relationships.user.data.id'
            : 'user';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.name' => 'required|string',
            'data.attributes.itemId' => 'required|string',
            'data.attributes.status' => 'required|string|in:pending,confirmed,cancelled',
            'data.attributes.slotTime' => 'required|string',
            $userIdAttr => 'required|integer|exists:users,id',
        
        ];

        // A user who may only create their own bookings must be the owner:
        // size:5 means the id must equal 5, the logged-in user's id.
        $user = $this->user();

        if ($user->tokenCan(Abilities::CreateOwnBooking)) {
            $rules[$userIdAttr] .= '|size:' . $user->id;
        }

        return $rules;
    
    
    }

    /**
     * Runs before validation. On /users/{user}/bookings the user id is in the URL,
     * so we copy it into a "user" field that the rules above can validate.
     */
    protected function prepareForValidation(): void
    {
        if ($this->routeIs('users.bookings.store')) {
            $this->merge([
                'user' => $this->route('user')->getKey(),
            ]);
        }
    }
}
