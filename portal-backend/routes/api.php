<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

// Import existing controllers (legacy)
use App\Http\Controllers\Api\AuthController as LegacyAuthController;
use App\Http\Controllers\Api\SettingsController as LegacySettingsController;
use App\Http\Controllers\Api\ArticleController as LegacyArticleController;
use App\Http\Controllers\Api\ActivityLogController as LegacyActivityLogController;
use App\Http\Controllers\Api\DashboardController as LegacyDashboardController;

// Import v1 controllers
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\GalleryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
| API Versioning: /api/v1/...
| Rate Limiting: 60 req/min (public), 120 req/min (authenticated)
|
*/

// ============================================================================
// Rate Limiters Configuration
// ============================================================================
RateLimiter::for('api-public', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});

RateLimiter::for('api-authenticated', function (Request $request) {
    return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
});

// ============================================================================
// API v1 Routes
// ============================================================================
Route::prefix('v1')->group(function () {
    
    // ----------------------------------------------------------
    // Public Routes (No Authentication Required)
    // Rate Limited: 60 requests per minute
    // ----------------------------------------------------------
    Route::middleware('throttle:api-public')->group(function () {
        
        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
            Route::post('/register', [AuthController::class, 'register'])->name('api.v1.auth.register');
        });

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('api.v1.settings.index');

        // Articles (public read)
        Route::prefix('articles')->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->name('api.v1.articles.index');
            Route::get('/featured', [ArticleController::class, 'featured'])->name('api.v1.articles.featured');
            Route::get('/popular', [ArticleController::class, 'popular'])->name('api.v1.articles.popular');
            Route::get('/{slug}', [ArticleController::class, 'show'])->name('api.v1.articles.show');
            Route::get('/{slug}/comments', [CommentController::class, 'index'])->name('api.v1.comments.index');
        });

        // Categories (public read)
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('api.v1.categories.index');
            Route::get('/{slug}', [CategoryController::class, 'show'])->name('api.v1.categories.show');
        });

        // Tags (public read)
        Route::prefix('tags')->group(function () {
            Route::get('/', [TagController::class, 'index'])->name('api.v1.tags.index');
            Route::get('/{slug}', [TagController::class, 'show'])->name('api.v1.tags.show');
        });

        // Gallery (public read)
        Route::prefix('gallery')->group(function () {
            Route::get('/', [GalleryController::class, 'index'])->name('api.v1.gallery.index');
            Route::get('/albums', [GalleryController::class, 'albums'])->name('api.v1.gallery.albums');
            Route::get('/{id}', [GalleryController::class, 'show'])->name('api.v1.gallery.show');
        });

        // Users (public profile)
        Route::get('/users/{id}', [UserController::class, 'show'])->name('api.v1.users.show');
    });

    // ----------------------------------------------------------
    // Protected Routes (Authentication Required)
    // Rate Limited: 120 requests per minute
    // ----------------------------------------------------------
    Route::middleware(['auth:sanctum', 'throttle:api-authenticated'])->group(function () {
        
        // Auth
        Route::prefix('auth')->group(function () {
            Route::get('/user', [AuthController::class, 'user'])->name('api.v1.auth.user');
            Route::put('/user', [AuthController::class, 'updateProfile'])->name('api.v1.auth.updateProfile');
            Route::put('/password', [AuthController::class, 'updatePassword'])->name('api.v1.auth.updatePassword');
            Route::post('/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
            Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('api.v1.auth.logoutAll');
        });

        // Articles (write)
        Route::prefix('articles')->group(function () {
            Route::post('/', [ArticleController::class, 'store'])->name('api.v1.articles.store');
            Route::put('/{id}', [ArticleController::class, 'update'])->name('api.v1.articles.update');
            Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('api.v1.articles.destroy');
            Route::post('/{slug}/like', [ArticleController::class, 'toggleLike'])->name('api.v1.articles.like');
        });

        // Comments (write)
        Route::prefix('articles/{slug}/comments')->group(function () {
            Route::post('/', [CommentController::class, 'store'])->name('api.v1.comments.store');
        });
        Route::prefix('comments')->group(function () {
            Route::put('/{id}', [CommentController::class, 'update'])->name('api.v1.comments.update');
            Route::delete('/{id}', [CommentController::class, 'destroy'])->name('api.v1.comments.destroy');
        });

        // Categories (admin write)
        Route::prefix('categories')->group(function () {
            Route::post('/', [CategoryController::class, 'store'])->name('api.v1.categories.store');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('api.v1.categories.update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('api.v1.categories.destroy');
        });

        // Tags (admin write)
        Route::prefix('tags')->group(function () {
            Route::post('/', [TagController::class, 'store'])->name('api.v1.tags.store');
            Route::put('/{id}', [TagController::class, 'update'])->name('api.v1.tags.update');
            Route::delete('/{id}', [TagController::class, 'destroy'])->name('api.v1.tags.destroy');
        });

        // Gallery (admin write)
        Route::prefix('gallery')->group(function () {
            Route::post('/', [GalleryController::class, 'store'])->name('api.v1.gallery.store');
            Route::put('/{id}', [GalleryController::class, 'update'])->name('api.v1.gallery.update');
            Route::delete('/{id}', [GalleryController::class, 'destroy'])->name('api.v1.gallery.destroy');
        });

        // Users (admin)
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('api.v1.users.index');
            Route::put('/{id}', [UserController::class, 'update'])->name('api.v1.users.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.v1.users.destroy');
        });

        // Settings (admin)
        Route::prefix('settings')->group(function () {
            Route::get('/group/{group}', [SettingsController::class, 'group'])->name('api.v1.settings.group');
            Route::put('/', [SettingsController::class, 'update'])->name('api.v1.settings.update');
        });
    });
});

// ============================================================================
// Legacy API Routes (for backward compatibility)
// These will be deprecated in future versions
// ============================================================================
Route::post('/login', [LegacyAuthController::class, 'login']);
Route::get('/settings/public', [LegacySettingsController::class, 'public']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LegacyAuthController::class, 'user']);
    Route::post('/logout', [LegacyAuthController::class, 'logout']);
    Route::get('/dashboard', [LegacyDashboardController::class, 'index']);

    Route::prefix('articles')->group(function () {
        Route::get('/', [LegacyArticleController::class, 'index']);
        Route::post('/', [LegacyArticleController::class, 'store']);
        Route::get('/{article}', [LegacyArticleController::class, 'show']);
        Route::put('/{article}', [LegacyArticleController::class, 'update']);
        Route::delete('/{article}', [LegacyArticleController::class, 'destroy']);
        Route::post('/bulk-delete', [LegacyArticleController::class, 'bulkDelete']);
        Route::post('/bulk-status', [LegacyArticleController::class, 'bulkUpdateStatus']);
    });

    Route::prefix('activity-logs')->group(function () {
        Route::get('/{activityLog}', [LegacyActivityLogController::class, 'show']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [LegacySettingsController::class, 'index']);
        Route::put('/', [LegacySettingsController::class, 'update']);
        Route::get('/group/{group}', [LegacySettingsController::class, 'group']);
        Route::get('/key/{key}', [LegacySettingsController::class, 'show']);
        Route::put('/key/{key}', [LegacySettingsController::class, 'updateSingle']);
        Route::post('/clear-cache', [LegacySettingsController::class, 'clearCache']);
    });
});
