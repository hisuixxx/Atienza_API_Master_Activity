<?php

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//TEMPORARY STEP 5
// Route::get('/ping', fn () => (new class {
//     use \App\Traits\ApiResponses;
    
//     public function run()
//     {
//         return $this->ok('API is workinhg');
//     }
// })->run());


//TEMPORARY: DELETE IN STEP 7
Route::get('/bookings', function () {
    return Booking::all();
});
