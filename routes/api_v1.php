<?php 

use App\Http\Controllers\Api\V1\BookingController;
use Illuminate\Support\Facades\Route;

Route::apiResource('bookings', BookingController::class)->only(['index']);