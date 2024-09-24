<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PresenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/get-presence-today', [PresenceController::class, 'getPresenceToday']); // ok
    Route::post('/store-presence', [PresenceController::class, 'store']); // ok
    Route::get('/get-presence-by-month-year/{month}/{year}', [PresenceController::class, 'getPresenceByMonthYear']); // ok
    Route::get('/get-employee', [PresenceController::class, 'getEmployee']); // ok
    Route::post('/banned', [PresenceController::class, 'banned']); // ok
    Route::get('/get-image', [PresenceController::class, 'getImage']);
});
