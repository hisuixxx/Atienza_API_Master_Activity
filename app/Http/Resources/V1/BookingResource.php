<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Build the array that becomes the JSON response.    
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            // What kind of object this is
            'type' => 'booking',
            'id' => $this->id,
            
            // The booking's own fields
            'attributes' => [
                'name' => $this->name,
                'itemId' => $this->item_id,
                'status' => $this->status,
                'slotTime' => $this->slot_time,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            
            // Related objects: the user who made the booking
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                ],
            ],
            
            // Link to this booking
            'links' => [
                'self' => route('bookings.show', ['booking' => $this->id]),
            ],
        ];
    }
}
