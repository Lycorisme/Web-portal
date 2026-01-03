<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Routes (protected by auth middleware when auth is implemented)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Settings Routes
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::put('/settings/{group}', [SettingsController::class, 'updateGroup'])->name('settings.update.group');

// Logout route (placeholder - will be implemented with auth)
Route::post('/logout', function () {
    // Auth::logout();
    return redirect('/');
})->name('logout');
