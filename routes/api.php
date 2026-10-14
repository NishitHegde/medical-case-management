<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Medical Case Management System
|--------------------------------------------------------------------------
*/

// Public Authentication Endpoint
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Endpoints via Laravel Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Case Management Endpoints
    Route::get('/cases', [CaseApiController::class, 'index']);
    Route::post('/cases', [CaseApiController::class, 'store']);
    Route::get('/cases/{id}', [CaseApiController::class, 'show']);
    Route::put('/cases/{id}', [CaseApiController::class, 'update']);
    Route::delete('/cases/{id}', [CaseApiController::class, 'destroy']);

    // Current authenticated user profile
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
            'message' => 'User profile retrieved successfully',
        ]);
    });
});
