<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
        return [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.itemId' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:pending,confirmed,cancelled',
            'data.attributes.slotTime' => 'sometimes|string',
            'data.relationships.user.data.id' => 'sometimes|integer|exists:users,id',
        
        ];
    }
}
