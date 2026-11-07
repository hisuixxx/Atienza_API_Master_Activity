<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // The user who made the booking
            $table->foreignId('user_id')->constrained();
            // Name of the booking
            $table->string('name');
            // The item being booked
            $table->unsignedBigInteger('item_id')->constrained();
            // Status of the booking (pending, confirmed, cancelled, completed)
            $table->string('status');
            // The scheduled date and time for the reservation
            $table->timestamp('slot_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
