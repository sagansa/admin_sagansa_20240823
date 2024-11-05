<?php

use app\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PresenceController;
use App\Http\Controllers\api\LeaveController;
use App\Http\Controllers\api\SalaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
