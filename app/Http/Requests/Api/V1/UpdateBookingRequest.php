<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Permissions\V1\Abilities;

class UpdateBookingRequest extends BaseBookingRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating a product. "Sometimes" means:
     * validate the field only when it was sent. 
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.itemId' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:pending,confirmed,cancelled',
            'data.attributes.slotTime' => 'sometimes|string',
            'data.relationships.user.data.id' => 'sometimes|integer|exists:users,id',
        ];

        // Users who may only update their own bookings cannot change the owner
        if ($this->user()->tokenCan(Abilities::UpdateOwnBooking)) {
            $rules['data.relationships.user.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
