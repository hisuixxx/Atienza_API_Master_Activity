<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * A product that belongs to a user (its author).
 * Fillable lists the columns that may be mass assigned with Product::create([...]).
 */
#[Fillable(['user_id', 'name', 'item_id', 'status', 'slot_time', 'created_at', 'updated_at'])]

class Booking extends Model
{
    //
    // Gives the model access to factories (used by seeders and tests).
    use HasFactory;

    /**
     * The user who made the booking. Each booking belongs to one user,
     * found through the user_id column.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}


