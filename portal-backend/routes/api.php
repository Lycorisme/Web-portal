<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);

// Public settings (for frontend)
Route::get('/settings/public', [SettingsController::class, 'public']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Settings routes (admin only)
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index']);
        Route::put('/', [SettingsController::class, 'update']);
        Route::get('/group/{group}', [SettingsController::class, 'group']);
        Route::get('/key/{key}', [SettingsController::class, 'show']);
        Route::put('/key/{key}', [SettingsController::class, 'updateSingle']);
        Route::post('/clear-cache', [SettingsController::class, 'clearCache']);
    });
});
