<?php 

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\UserBookingsController;

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

    // GET /bookings (list) and GET /bookings/{booking} (one booking)
    Route::apiResource('bookings', BookingController::class)->only(['index', 'show', 'store', 'destroy']);

    // PUT replaces the whole booking (custom method name: replace)
    Route::put('bookings/{booking}', [BookingController::class, 'replace']);
   
    // PATCH updates only the fields that were sent
    Route::patch('bookings/{booking}', [BookingController::class, 'update']);

    // Authors are read only: list and show
    Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

    // Authors are read only: list and show
    Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

    // Bookings of one user: /users/{user}/bookings
    Route::apiResource('users.bookings', UserBookingsController::class)
        ->only(['index', 'store', 'destroy'])
        ->scoped(); // the booking must belong to the user in the URL (otherwise 404)
        
    // PUT and PATCH use our own method names, so they are registered by hand
    Route::put('users/{user}/bookings/{booking}', [UserBookingsController::class, 'replace'])
        ->scopeBindings();
    Route::patch('users/{user}/bookings/{booking}', [UserBookingsController::class, 'update'])
        ->scopeBindings();});