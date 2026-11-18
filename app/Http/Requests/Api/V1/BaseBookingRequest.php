<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


 /**
 * Shared by every booking request (store, replace, update).
 */

class BaseBookingRequest extends FormRequest
{
    // /**
    //  * Determine if the user is authorized to make this request.
    //  */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    // /**
    //  * Get the validation rules that apply to the request.
    //  *
    //  * @return array<string, ValidationRule|array<mixed>|string>
    //  */
    // public function rules(): array
    // {
    //     return [
    //         //
    //     ];
    // }

    /**
     * Converts the nested JSON:API payload into database columns.
     * Only keys that were actually sent are returned, which is what makes PATCH work.
     */
    public function mappedAttributes(array $otherAttributes = []): array
    {
        // JSON path in the request => column in the bookings table
        $attributeMap = array_merge([
            'data.attributes.name' => 'name',
            'data.attributes.itemId' => 'item_id',
            'data.attributes.status' => 'status',
            'data.attributes.slotTime' => 'slot_time',
            'data.relationships.user.data.id' => 'user_id',
        ], $otherAttributes);

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            // Keep the value only if the client sent that field
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    /**
     * Custom validation messages. The key format is "field.rule".
     */
    public function messages(): array
    {
        return [
            'data.attributes.status.in' => 'The data.attributes.status value is invalid.',
        ];
    }

}
