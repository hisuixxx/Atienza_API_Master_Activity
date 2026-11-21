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
     * Validation rules for updating a product. 
     * Validation rules for updating a booking (deny by default).    
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.itemId' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:pending,confirmed,cancelled',
            'data.attributes.slotTime' => 'sometimes|string',
            // Default: nobody may change the user
        'data.relationships.user.data.id' => 'prohibited',
        ];

        // Only the broad ability lifts the restriction (managers)
        if ($this->user()->tokenCan(Abilities::UpdateBooking)) {
            $rules['data.relationships.user.data.id'] = 'sometimes|integer|exists:users,id';
        }

        return $rules;
    }
}
