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
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\BlockedClientController;
use App\Http\Controllers\ReportController;

// =============================================
// Authentication Routes (Guest Only)
// =============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Password Reset Routes (OTP based)
    Route::post('/password/send-otp', [\App\Http\Controllers\PasswordResetController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/password/verify-otp', [\App\Http\Controllers\PasswordResetController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/password/reset', [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.reset');
    Route::post('/password/resend-otp', [\App\Http\Controllers\PasswordResetController::class, 'resendOtp'])->name('password.resend-otp');

    // Email Verification Routes
    Route::get('/verify-email', [AuthController::class, 'showVerifyForm'])->name('verification.notice');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/verify-email/resend', [AuthController::class, 'resendVerificationOtp'])->name('verification.resend');
});

// Logout (Authenticated Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect root to public home
Route::get('/', function () {
    return redirect()->route('public.home');
});

// =============================================
// Public Routes (No Auth Required for Reading)
// =============================================
Route::prefix('p')->name('public.')->group(function () {
    // Read-only content
    Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home');
    Route::get('/artikel', [\App\Http\Controllers\PublicController::class, 'listArticles'])->name('articles');
    Route::get('/artikel/{slug}', [\App\Http\Controllers\PublicController::class, 'showArticle'])->name('article.show');
    Route::get('/galeri', [\App\Http\Controllers\PublicController::class, 'showGallery'])->name('gallery');
    Route::get('/maintenance', [\App\Http\Controllers\PublicController::class, 'showMaintenance'])->name('maintenance');
    
    // API for AJAX requests
    Route::get('/api/articles-by-category', [\App\Http\Controllers\PublicController::class, 'getArticlesByCategory'])->name('api.articles-by-category');

    // Authenticated interactions (Like, Comment)
    Route::middleware('auth')->group(function () {
        Route::post('/artikel/{article}/like', [\App\Http\Controllers\PublicInteractionController::class, 'toggleLike'])->name('article.like');
        Route::post('/artikel/{article}/comment', [\App\Http\Controllers\PublicInteractionController::class, 'storeComment'])->name('article.comment');
        Route::post('/comment/{comment}/reply', [\App\Http\Controllers\PublicInteractionController::class, 'storeReply'])->name('comment.reply');
        Route::put('/comment/{comment}', [\App\Http\Controllers\PublicInteractionController::class, 'updateComment'])->name('comment.update');
        Route::delete('/comment/{comment}', [\App\Http\Controllers\PublicInteractionController::class, 'deleteComment'])->name('comment.delete');
        
        // Public Profile Routes (Member Only)
        Route::prefix('profil')->name('profile.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PublicProfileController::class, 'index'])->name('index');
            Route::put('/info', [\App\Http\Controllers\PublicProfileController::class, 'updateInfo'])->name('info');
            Route::post('/photo', [\App\Http\Controllers\PublicProfileController::class, 'updatePhoto'])->name('photo');
            Route::delete('/photo', [\App\Http\Controllers\PublicProfileController::class, 'deletePhoto'])->name('photo.delete');
            Route::put('/password', [\App\Http\Controllers\PublicProfileController::class, 'updatePassword'])->name('password');
            Route::post('/logout-all', [\App\Http\Controllers\PublicProfileController::class, 'logoutAllDevices'])->name('logout-all');
            Route::delete('/delete', [\App\Http\Controllers\PublicProfileController::class, 'deleteAccount'])->name('delete');
        });
    });
});



// =============================================
// Protected Routes (Auth Required)
// =============================================
Route::middleware('auth')->group(function () {
    // Dashboard Routes - All authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes - All authenticated users
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/logout-all-devices', [ProfileController::class, 'logoutAllDevices'])->name('profile.logout-all-devices');

    // Article Routes - All authenticated users (with role-based filtering in controller)
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

    // Gallery Routes - All authenticated users
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries');
    Route::get('/galleries/data', [GalleryController::class, 'getData'])->name('galleries.data');
    Route::get('/galleries/grouped', [GalleryController::class, 'getGroupedData'])->name('galleries.grouped');
    Route::get('/galleries/albums', [GalleryController::class, 'getAlbums'])->name('galleries.albums');
    Route::get('/galleries/album-items', [GalleryController::class, 'getAlbumItems'])->name('galleries.album-items');
    Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::post('/galleries/bulk-store', [GalleryController::class, 'bulkStore'])->name('galleries.bulk-store');
    Route::get('/galleries/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
    Route::delete('/galleries/bulk', [GalleryController::class, 'bulkDestroy'])->name('galleries.bulk-destroy');
    Route::post('/galleries/bulk-restore', [GalleryController::class, 'bulkRestore'])->name('galleries.bulk-restore');
    Route::delete('/galleries/bulk-force', [GalleryController::class, 'bulkForceDelete'])->name('galleries.bulk-force-delete');
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
    Route::post('/galleries/{id}/restore', [GalleryController::class, 'restore'])->name('galleries.restore');
    Route::delete('/galleries/{id}/force', [GalleryController::class, 'forceDelete'])->name('galleries.force-delete');
    Route::post('/galleries/{gallery}/toggle-published', [GalleryController::class, 'togglePublished'])->name('galleries.toggle-published');
    Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])->name('galleries.toggle-featured');

    // Trash Routes - All authenticated users (with role-based filtering in controller)
    Route::get('/trash', [TrashController::class, 'index'])->name('trash');
    Route::get('/trash/count', [TrashController::class, 'getCount'])->name('trash.count');
    Route::get('/trash/data', [TrashController::class, 'getData'])->name('trash.data');
    Route::post('/trash/{type}/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{type}/{id}/force', [TrashController::class, 'forceDelete'])->name('trash.force-delete');
    Route::post('/trash/bulk-restore', [TrashController::class, 'bulkRestore'])->name('trash.bulk-restore');
    Route::delete('/trash/bulk-force', [TrashController::class, 'bulkForceDelete'])->name('trash.bulk-force-delete');
    Route::delete('/trash/empty', [TrashController::class, 'emptyTrash'])->name('trash.empty');

    // =============================================
    // Admin & Editor Routes (super_admin, admin, editor)
    // =============================================
    Route::middleware('role:super_admin,admin,editor')->group(function () {
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
        Route::post('/tags/{tag}/toggle-active', [TagController::class, 'toggleActive'])->name('tags.toggle-active');
    });

    // =============================================
    // Admin Only Routes (super_admin, admin)
    // =============================================
    Route::middleware('role:super_admin,admin')->group(function () {
        // Settings Routes
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('/settings/{group}', [SettingsController::class, 'updateGroup'])->name('settings.update.group');

        // Report Routes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/reports/articles', [ReportController::class, 'generateArticleReport'])->name('reports.articles');
        Route::get('/reports/categories', [ReportController::class, 'generateCategoryReport'])->name('reports.categories');
        Route::get('/reports/users', [ReportController::class, 'generateUserReport'])->name('reports.users');
        Route::get('/reports/activity-logs', [ReportController::class, 'generateActivityLogReport'])->name('reports.activity-logs');
        Route::get('/reports/blocked-clients', [ReportController::class, 'generateBlockedClientReport'])->name('reports.blocked-clients');
        Route::get('/reports/galleries', [ReportController::class, 'generateGalleryReport'])->name('reports.galleries');

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

        // User Management Routes
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/bulk', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
        Route::post('/users/bulk-restore', [UserController::class, 'bulkRestore'])->name('users.bulk-restore');
        Route::delete('/users/bulk-force', [UserController::class, 'bulkForceDelete'])->name('users.bulk-force-delete');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');

        // Blocked Clients (IP Block) Routes
        Route::get('/blocked-clients', [BlockedClientController::class, 'index'])->name('blocked-clients');
        Route::get('/blocked-clients/count', [BlockedClientController::class, 'getCount'])->name('blocked-clients.count');
        Route::get('/blocked-clients/data', [BlockedClientController::class, 'getData'])->name('blocked-clients.data');
        Route::post('/blocked-clients', [BlockedClientController::class, 'store'])->name('blocked-clients.store');
        
        // Bulk actions MUST be defined BEFORE wildcard routes
        Route::post('/blocked-clients/bulk-unblock', [BlockedClientController::class, 'bulkUnblock'])->name('blocked-clients.bulk-unblock');
        Route::delete('/blocked-clients/bulk', [BlockedClientController::class, 'bulkDestroy'])->name('blocked-clients.bulk-destroy');
        Route::post('/blocked-clients/clear-expired', [BlockedClientController::class, 'clearExpired'])->name('blocked-clients.clear-expired');
        
        // Wildcard routes MUST be defined AFTER static routes
        Route::get('/blocked-clients/{blockedClient}', [BlockedClientController::class, 'show'])->name('blocked-clients.show');
        Route::put('/blocked-clients/{blockedClient}', [BlockedClientController::class, 'update'])->name('blocked-clients.update');
        Route::delete('/blocked-clients/{blockedClient}', [BlockedClientController::class, 'destroy'])->name('blocked-clients.destroy');
        Route::post('/blocked-clients/{blockedClient}/unblock', [BlockedClientController::class, 'unblock'])->name('blocked-clients.unblock');
    });
});
