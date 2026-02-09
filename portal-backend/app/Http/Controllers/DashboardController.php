<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\BlockedClient;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     * Dashboard content is dynamically filtered based on user role:
     * - Super Admin/Admin: Global view (all data, security stats)
     * - Editor: Task-based view (all pending articles for review)
     * - Author: Personal view (only their own articles/stats)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $isAuthor = $user->isAuthor();
        $isEditor = $user->isEditor();
        $isAdmin = $user->isSuperAdmin() || in_array($user->role, ['admin']);

        // Redirect if user cannot access dashboard (e.g. Member)
        if (!$user->canAccessDashboard()) {
            return redirect()->route('public.home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        // Build base query based on role
        $articleQuery = $this->getArticleQueryByRole($user);
        
        // Statistics from database (role-filtered)
        $totalArticles = (clone $articleQuery)->count();
        $publishedArticles = (clone $articleQuery)->where('status', 'published')->count();
        $draftArticles = (clone $articleQuery)->where('status', 'draft')->count();
        $pendingArticles = $this->getPendingCount($user);
        
        // Total views (role-filtered)
        $totalViews = (clone $articleQuery)->sum('views');
        $formattedViews = $this->formatNumber($totalViews);
        
        // Active admins (users who logged in within last 30 days) - Admin/Editor only
        $activeAdmins = 0;
        $totalAdmins = 0;
        if (!$isAuthor) {
            $activeAdmins = User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subDays(30))
                ->count();
            $totalAdmins = User::count();
        }
        
        // Blocked IPs count - Admin only (security feature)
        $blockedIps = 0;
        if ($isAdmin) {
            $blockedIps = BlockedClient::activeBlocks()->count();
        }
        
        // Calculate growth percentages (role-filtered)
        $articlesThisMonth = (clone $articleQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $articlesLastMonth = (clone $articleQuery)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $articleGrowth = $articlesLastMonth > 0 
            ? round((($articlesThisMonth - $articlesLastMonth) / $articlesLastMonth) * 100) 
            : ($articlesThisMonth > 0 ? 100 : 0);
        
        // Views growth (role-filtered)
        $viewsThisMonth = (clone $articleQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('views');
        $viewsLastMonth = (clone $articleQuery)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('views');
        $viewsGrowth = $viewsLastMonth > 0 
            ? round((($viewsThisMonth - $viewsLastMonth) / $viewsLastMonth) * 100) 
            : ($viewsThisMonth > 0 ? 100 : 0);
        
        // Failed logins in last 24 hours - Admin only
        $failedLogins = 0;
        if ($isAdmin) {
            $failedLogins = ActivityLog::where('action', ActivityLog::ACTION_LOGIN_FAILED)
                ->where('created_at', '>=', now()->subHours(24))
                ->count();
        }
        
        // Stats array with role context
        // If no real data exists, provide demo data
        $hasRealData = $totalArticles > 0;
        
        $stats = [
            'total_articles' => $hasRealData ? $totalArticles : 5,
            'published_articles' => $hasRealData ? $publishedArticles : 3,
            'draft_articles' => $hasRealData ? $draftArticles : 1,
            'pending_articles' => $hasRealData ? $pendingArticles : 1,
            'total_views' => $hasRealData ? $formattedViews : '2.1K',
            'total_views_raw' => $hasRealData ? $totalViews : 2103,
            'active_admins' => $activeAdmins,
            'total_admins' => $totalAdmins,
            'blocked_ips' => $blockedIps,
            'failed_logins' => $failedLogins,
            'article_growth' => $hasRealData ? $articleGrowth : 15,
            'views_growth' => $hasRealData ? $viewsGrowth : 23,
            // Role context for view labels
            'is_author' => $isAuthor,
            'is_editor' => $isEditor,
            'is_admin' => $isAdmin,
            'is_demo' => !$hasRealData, // Flag for demo data
        ];

        // Recent articles (role-filtered)
        $recentArticles = (clone $articleQuery)
            ->with(['author', 'categoryRelation'])
            ->latest('updated_at')
            ->take(5)
            ->get();
        
        // Add dummy articles for demo if no real articles exist
        if ($recentArticles->isEmpty()) {
            $dummyArticles = $this->getDummyArticles($user);
            $recentArticles = collect($dummyArticles);
        }

        // Recent activity logs - Admin only, or own activities for others
        // Exclude VIEW actions to prevent cluttering the log with page views
        if ($isAdmin) {
            $activityLogs = ActivityLog::with('user')
                ->where('action', '!=', ActivityLog::ACTION_VIEW)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } else {
            // Non-admins see only their own activity
            $activityLogs = ActivityLog::with('user')
                ->where('user_id', $user->id)
                ->where('action', '!=', ActivityLog::ACTION_VIEW)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        // Category distribution for chart (role-filtered for authors)
        if ($isAuthor) {
            // Authors see distribution of their own articles
            $categories = Category::withCount(['articles' => function ($query) use ($user) {
                    $query->where('author_id', $user->id);
                }])
                ->having('articles_count', '>', 0)
                ->orderBy('articles_count', 'desc')
                ->take(5)
                ->get();
        } else {
            $categories = Category::withCount('articles')
                ->orderBy('articles_count', 'desc')
                ->take(5)
                ->get();
        }
        
        // Calculate category percentages
        $totalCategoryArticles = $categories->sum('articles_count');
        
        // If no category data, provide dummy data for demo
        if ($totalCategoryArticles == 0) {
            $categoryData = collect([
                ['name' => 'Teknologi', 'count' => 12, 'percentage' => 40, 'color' => '#6366f1'],
                ['name' => 'Keamanan', 'count' => 8, 'percentage' => 27, 'color' => '#10b981'],
                ['name' => 'Infrastruktur', 'count' => 5, 'percentage' => 17, 'color' => '#f59e0b'],
                ['name' => 'Pelayanan', 'count' => 3, 'percentage' => 10, 'color' => '#ec4899'],
                ['name' => 'Lainnya', 'count' => 2, 'percentage' => 6, 'color' => '#8b5cf6'],
            ]);
        } else {
            $categoryData = $categories->map(function ($category) use ($totalCategoryArticles) {
                $percentage = $totalCategoryArticles > 0 
                    ? round(($category->articles_count / $totalCategoryArticles) * 100) 
                    : 0;
                return [
                    'name' => $category->name,
                    'count' => $category->articles_count,
                    'percentage' => $percentage,
                    'color' => $category->color ?? '#6366f1',
                ];
            });
        }

        // Visit statistics for the last 7 days (role-filtered)
        // Use ActivityLog to get real daily view counts
        $visitStats = [];
        
        // Cache author article IDs if needed to avoid queries in loop
        $authorArticleIds = $isAuthor ? Article::where('author_id', $user->id)->pluck('id') : null;

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $this->getDayName($date->dayOfWeek);
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();
            
            // Get views from ActivityLog using range to handle timezone differences correctly
            $dayViewsQuery = ActivityLog::where('action', ActivityLog::ACTION_VIEW)
                ->whereBetween('created_at', [$startOfDay, $endOfDay]);
                
            if ($isAuthor && $authorArticleIds) {
                $dayViewsQuery->where('subject_type', Article::class)
                    ->whereIn('subject_id', $authorArticleIds);
            }
            
            $dayViews = $dayViewsQuery->count();
            
            $visitStats[] = [
                'day' => $dayName,
                'date' => $date->format('d M'),
                'views' => $dayViews, // Count of views today
            ];
        }
        
        // Normalize visit stats for chart height (as percentage of max)
        $maxViews = max(array_column($visitStats, 'views')) ?: 1;
        
        // If no real views, provide dummy data for demo
        $totalDayViews = array_sum(array_column($visitStats, 'views'));
        if ($totalDayViews == 0) {
            // Generate demo visit data with realistic patterns
            $dummyViews = [45, 32, 58, 41, 67, 53, 38]; // Sample visit counts
            foreach ($visitStats as $index => &$stat) {
                $stat['views'] = $dummyViews[$index] ?? rand(30, 70);
            }
            $maxViews = max(array_column($visitStats, 'views')) ?: 1;
        }
        
        $visitStats = array_map(function ($stat) use ($maxViews) {
            $stat['percentage'] = round(($stat['views'] / $maxViews) * 100);
            $stat['percentage'] = max(5, $stat['percentage']); // Minimum 5% height
            return $stat;
        }, $visitStats);

        // Security score calculation - Admin only
        $securityScore = $isAdmin ? $this->calculateSecurityScore($blockedIps) : null;

        // Prepare article data for modal JS
        $articlesForModal = [];
        foreach ($recentArticles as $article) {
            // Handle thumbnail path - may be stored with /storage/ prefix or without
            $thumbnailUrl = null;
            if ($article->thumbnail) {
                if (str_starts_with($article->thumbnail, '/storage/') || str_starts_with($article->thumbnail, 'storage/')) {
                    $thumbnailUrl = asset($article->thumbnail);
                } elseif (str_starts_with($article->thumbnail, 'http')) {
                    $thumbnailUrl = $article->thumbnail;
                } else {
                    $thumbnailUrl = asset('storage/' . $article->thumbnail);
                }
            }
            
            // Handle date formatting (works for both Eloquent models and stdClass)
            $createdAt = $article->created_at instanceof Carbon ? $article->created_at->format('d M Y, H:i') : (string) $article->created_at;
            $updatedAt = $article->updated_at instanceof Carbon ? $article->updated_at->format('d M Y, H:i') : (string) $article->updated_at;
            $publishedAt = isset($article->published_at) && $article->published_at instanceof Carbon ? $article->published_at->format('d M Y, H:i') : null;
            
            $articlesForModal[$article->id] = [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt ?? null,
                'content' => $article->content,
                'thumbnail' => $thumbnailUrl,
                'status' => $article->status,
                'views' => $article->views,
                'read_time' => $article->read_time ?? 1,
                'author_name' => $article->author->name ?? 'Admin',
                'author_avatar' => isset($article->author) && $article->author->avatar ? asset('storage/' . $article->author->avatar) : null,
                'category_name' => $article->categoryRelation->name ?? $article->category ?? null,
                'category_color' => $article->categoryRelation->color ?? '#6366f1',
                'category_icon' => $article->categoryRelation->icon ?? 'folder',
                'meta_title' => $article->meta_title ?? null,
                'meta_description' => $article->meta_description ?? null,
                'published_at' => $publishedAt,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        // Prepare activity log data for modal JS
        $actionLabels = [
            'CREATE' => 'Membuat',
            'UPDATE' => 'Mengubah',
            'DELETE' => 'Menghapus',
            'LOGIN' => 'Login',
            'LOGOUT' => 'Logout',
            'LOGIN_FAILED' => 'Login Gagal',
            'RESTORE' => 'Memulihkan',
            'FORCE_DELETE' => 'Hapus Permanen',
        ];
        
        $activityLogsForModal = [];
        foreach ($activityLogs as $log) {
            $activityLogsForModal[$log->id] = [
                'id' => $log->id,
                'action' => $log->action,
                'action_label' => $actionLabels[$log->action] ?? $log->action,
                'level' => $log->level ?? 'info',
                'description' => $log->description,
                'subject_type' => $log->subject_type ? class_basename($log->subject_type) : null,
                'subject_id' => $log->subject_id,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'user_name' => $log->user->name ?? 'System',
                'user_avatar' => $log->user && $log->user->avatar ? asset('storage/' . $log->user->avatar) : null,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'url' => $log->url,
                'created_at' => $log->created_at->format('d M Y, H:i:s'),
                'created_at_human' => $log->created_at->diffForHumans(),
            ];
        }

        return view('dashboard', compact(
            'stats',
            'recentArticles',
            'activityLogs',
            'categoryData',
            'visitStats',
            'totalCategoryArticles',
            'securityScore',
            'articlesForModal',
            'activityLogsForModal'
        ));
    }
    
    /**
     * Get article query filtered by user role.
     * Authors only see their own articles.
     * Editors/Admins see all articles.
     */
    private function getArticleQueryByRole(User $user)
    {
        if ($user->isAuthor()) {
            return Article::where('author_id', $user->id);
        }
        
        return Article::query();
    }
    
    /**
     * Get pending articles count based on role.
     * - Authors: Only their own pending articles (personal progress)
     * - Editors/Admins: All pending articles (review queue)
     */
    private function getPendingCount(User $user): int
    {
        if ($user->isAuthor()) {
            // Authors see their own pending count
            return Article::where('author_id', $user->id)
                ->where('status', 'pending')
                ->count();
        }
        
        // Editors/Admins see total review queue
        return Article::where('status', 'pending')->count();
    }

    /**
     * Format large numbers for display
     */
    private function formatNumber($number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string) $number;
    }

    /**
     * Get Indonesian day name
     */
    private function getDayName(int $dayOfWeek): string
    {
        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        return $days[$dayOfWeek];
    }

    /**
     * Calculate security score based on various factors
     */
    private function calculateSecurityScore(int $blockedIps): int
    {
        // Start with 100%
        $score = 100;
        
        // Deduct points for blocked IPs (more blocked = potential security issues being caught)
        // But too many could indicate attacks
        if ($blockedIps > 10) {
            $score -= 5;
        }
        if ($blockedIps > 50) {
            $score -= 10;
        }
        
        // Check for failed login attempts in last 24 hours
        $recentFailedLogins = ActivityLog::where('action', ActivityLog::ACTION_LOGIN_FAILED)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();
        
        if ($recentFailedLogins > 20) {
            $score -= 10;
        } elseif ($recentFailedLogins > 10) {
            $score -= 5;
        }
        
        return max(0, min(100, $score));
    }

    /**
     * Generate dummy articles for demo purposes
     * Uses existing images from galleries folder
     */
    private function getDummyArticles(User $user): array
    {
        $dummyData = [
            [
                'id' => 'demo-1',
                'title' => 'Peluncuran Program Digitalisasi UMKM Tahun 2026',
                'slug' => 'peluncuran-program-digitalisasi-umkm-2026',
                'content' => 'Program digitalisasi UMKM yang diinisiasi oleh pemerintah bertujuan untuk meningkatkan daya saing usaha mikro, kecil, dan menengah di era digital. Program ini mencakup pelatihan e-commerce, pembukuan digital, dan pemasaran online.',
                'excerpt' => 'Program digitalisasi UMKM untuk meningkatkan daya saing usaha...',
                'thumbnail' => '/storage/galleries/1767844442_695f2a5a8d11c.jpg',
                'status' => 'published',
                'views' => 1247,
                'read_time' => 5,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(6),
            ],
            [
                'id' => 'demo-2',
                'title' => 'Workshop Keamanan Siber untuk Instansi Pemerintah',
                'slug' => 'workshop-keamanan-siber-instansi-pemerintah',
                'content' => 'BTIKP menyelenggarakan workshop keamanan siber yang dihadiri oleh perwakilan dari berbagai instansi pemerintah. Materi yang disampaikan meliputi best practices keamanan data, penanganan insiden siber, dan implementasi zero trust architecture.',
                'excerpt' => 'Workshop keamanan siber yang dihadiri perwakilan instansi...',
                'thumbnail' => '/storage/galleries/1767848791_695f3b57661f0.jpg',
                'status' => 'published',
                'views' => 856,
                'read_time' => 4,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id' => 'demo-3',
                'title' => 'Rencana Pembangunan Data Center Regional',
                'slug' => 'rencana-pembangunan-data-center-regional',
                'content' => 'Pemerintah daerah berencana membangun data center regional untuk mendukung transformasi digital di berbagai sektor. Fasilitas ini akan menjadi backbone infrastruktur teknologi informasi untuk pelayanan publik yang lebih efisien.',
                'excerpt' => 'Pembangunan data center regional untuk transformasi digital...',
                'thumbnail' => '/storage/galleries/1769425214_6977493e36d5a.JPEG',
                'status' => 'draft',
                'views' => 0,
                'read_time' => 3,
                'created_at' => now()->subHours(12),
                'updated_at' => now()->subHours(2),
            ],
        ];

        // Convert to objects with necessary relationships
        return array_map(function ($data) use ($user) {
            $article = new \stdClass();
            $article->id = $data['id'];
            $article->title = $data['title'];
            $article->slug = $data['slug'];
            $article->content = $data['content'];
            $article->excerpt = $data['excerpt'];
            $article->thumbnail = $data['thumbnail'];
            $article->status = $data['status'];
            $article->views = $data['views'];
            $article->read_time = $data['read_time'];
            $article->created_at = $data['created_at'];
            $article->updated_at = $data['updated_at'];
            
            // Mock author relationship
            $article->author = (object) [
                'name' => $user->name,
                'avatar' => $user->avatar,
            ];
            
            // Mock category relationship
            $article->categoryRelation = (object) [
                'name' => 'Teknologi',
                'color' => '#6366f1',
                'icon' => 'cpu',
            ];
            $article->category = 'Teknologi';
            
            return $article;
        }, $dummyData);
    }
}
