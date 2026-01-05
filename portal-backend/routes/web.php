<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Routes (protected by auth middleware when auth is implemented)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Settings Routes
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::put('/settings/{group}', [SettingsController::class, 'updateGroup'])->name('settings.update.group');

// Activity Log Routes
Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');
Route::get('/activity-log/data', [ActivityLogController::class, 'getData'])->name('activity-log.data');
Route::get('/activity-log/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-log.show');
Route::delete('/activity-log/bulk', [ActivityLogController::class, 'bulkDestroy'])->name('activity-log.bulk-destroy');
Route::post('/activity-log/bulk-restore', [ActivityLogController::class, 'bulkRestore'])->name('activity-log.bulk-restore');
Route::delete('/activity-log/bulk-force', [ActivityLogController::class, 'bulkForceDelete'])->name('activity-log.bulk-force-delete');
Route::delete('/activity-log/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-log.destroy');
Route::post('/activity-log/{id}/restore', [ActivityLogController::class, 'restore'])->name('activity-log.restore');
Route::delete('/activity-log/{id}/force', [ActivityLogController::class, 'forceDelete'])->name('activity-log.force-delete');
Route::post('/activity-log/clear-old', [ActivityLogController::class, 'clearOld'])->name('activity-log.clear-old');

// Logout route (placeholder - will be implemented with auth)
Route::post('/logout', function () {
    // Auth::logout();
    return redirect('/');
})->name('logout');
