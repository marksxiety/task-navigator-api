<?php

use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\ModeController;
use App\Http\Controllers\Api\SystemController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::apiResource('status', StatusController::class);
    Route::apiResource('mode', ModeController::class);
    Route::apiResource('system', SystemController::class);
    Route::apiResource('task', TaskController::class);
});




