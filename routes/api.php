<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ModelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public car routes
Route::apiResource('cars', CarController::class);

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Car assignment routes
    Route::post('cars/{car}/assign', [CarController::class, 'assign']);
    Route::post('cars/{car}/unassign', [CarController::class, 'unassign']);
});


Route::apiResource('brands', BrandController::class)->only(['index']);
Route::apiResource('car_models', ModelController::class)->only(['index']);
