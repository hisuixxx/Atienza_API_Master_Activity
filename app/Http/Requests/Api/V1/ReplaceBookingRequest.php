<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReplaceBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * PUT replaces the whole resource, so nothing may be missing.
     */
    public function rules(): array
    {
        return [
          'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.name' => 'required|string',
            'data.attributes.itemId' => 'required|string',
            'data.attributes.status' => 'required|string|in:pending,confirmed,cancelled',
            'data.attributes.slotTime' => 'required|string',
            'data.relationships' => 'required|array',
            'data.relationships.user' => 'required|array',
            'data.relationships.user.data' => 'required|array',
            'data.relationships.user.data.id' => 'required|integer|exists:users,id',
        ];
    }
}
