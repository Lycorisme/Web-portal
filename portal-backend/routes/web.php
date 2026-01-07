<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleInteractionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;

// =============================================
// Authentication Routes (Guest Only)
// =============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout (Authenticated Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// =============================================
// Protected Routes (Auth Required)
// =============================================
Route::middleware('auth')->group(function () {
    // Dashboard Routes
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

    // Article Routes
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
    Route::get('/articles/data', [ArticleController::class, 'getData'])->name('articles.data');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/bulk', [ArticleController::class, 'bulkDestroy'])->name('articles.bulk-destroy');
    Route::post('/articles/bulk-restore', [ArticleController::class, 'bulkRestore'])->name('articles.bulk-restore');
    Route::delete('/articles/bulk-force', [ArticleController::class, 'bulkForceDelete'])->name('articles.bulk-force-delete');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{id}/restore', [ArticleController::class, 'restore'])->name('articles.restore');
    Route::delete('/articles/{id}/force', [ArticleController::class, 'forceDelete'])->name('articles.force-delete');
    Route::post('/articles/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('articles.toggle-status');

    // Article Interaction Routes (Statistics, Comments, Likes)
    Route::get('/articles/{article}/activities', [ArticleController::class, 'getActivities'])->name('articles.activities');
    Route::get('/articles/{article}/statistics', [ArticleInteractionController::class, 'getStatistics'])->name('articles.statistics');
    Route::get('/articles/{article}/comments', [ArticleInteractionController::class, 'getComments'])->name('articles.comments');
    Route::post('/comments/{comment}/reply', [ArticleInteractionController::class, 'addAdminReply'])->name('comments.reply');
    Route::patch('/comments/{comment}/status', [ArticleInteractionController::class, 'updateCommentStatus'])->name('comments.update-status');
    Route::delete('/comments/{comment}', [ArticleInteractionController::class, 'deleteComment'])->name('comments.delete');
    Route::post('/comments/{comment}/restore', [ArticleInteractionController::class, 'restoreComment'])->name('comments.restore');
    Route::delete('/comments/{comment}/force', [ArticleInteractionController::class, 'forceDeleteComment'])->name('comments.force-delete');

    // Tag Routes
    Route::get('/tags', [TagController::class, 'index'])->name('tags');
    Route::get('/tags/data', [TagController::class, 'getData'])->name('tags.data');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/bulk', [TagController::class, 'bulkDestroy'])->name('tags.bulk-destroy');
    Route::post('/tags/bulk-restore', [TagController::class, 'bulkRestore'])->name('tags.bulk-restore');
    Route::delete('/tags/bulk-force', [TagController::class, 'bulkForceDelete'])->name('tags.bulk-force-delete');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::post('/tags/{id}/restore', [TagController::class, 'restore'])->name('tags.restore');
    Route::delete('/tags/{id}/force', [TagController::class, 'forceDelete'])->name('tags.force-delete');
});

