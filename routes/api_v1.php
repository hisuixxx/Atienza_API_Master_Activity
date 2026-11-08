<?php 

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use Illuminate\Support\Facades\Route;

// Route::apiResource('bookings', BookingController::class)->only(['index']);

// Public routes: no token needed
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes: the auth:api middleware rejects requests without a valid token (401)
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::apiResource('bookings', BookingController::class)->only(['index']);
});