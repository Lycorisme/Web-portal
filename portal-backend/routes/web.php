<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

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
Route::get('/activity-log/settings', [ActivityLogController::class, 'getSettings'])->name('activity-log.settings');
Route::put('/activity-log/settings', [ActivityLogController::class, 'updateSettings'])->name('activity-log.settings.update');
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

// Profile Routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info.update');
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/categories/data', [CategoryController::class, 'getData'])->name('categories.data');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/bulk', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');
Route::post('/categories/bulk-restore', [CategoryController::class, 'bulkRestore'])->name('categories.bulk-restore');
Route::delete('/categories/bulk-force', [CategoryController::class, 'bulkForceDelete'])->name('categories.bulk-force-delete');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
Route::delete('/categories/{id}/force', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
Route::post('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
Route::post('/categories/update-sort', [CategoryController::class, 'updateSort'])->name('categories.update-sort');

// Logout route (placeholder - will be implemented with auth)
Route::post('/logout', function () {
    // Auth::logout();
    return redirect('/');
})->name('logout');
