<?php

use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\ModeController;
use App\Http\Controllers\Api\SystemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('status', StatusController::class);
Route::apiResource('mode', ModeController::class);
Route::apiResource('system', SystemController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
