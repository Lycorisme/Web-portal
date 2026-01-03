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

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Statistics from database
        $totalArticles = Article::count();
        $publishedArticles = Article::published()->count();
        $draftArticles = Article::draft()->count();
        $pendingArticles = Article::pending()->count();
        
        // Total views from all articles
        $totalViews = Article::sum('views');
        $formattedViews = $this->formatNumber($totalViews);
        
        // Active admins (users who logged in within last 30 days)
        $activeAdmins = User::whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subDays(30))
            ->count();
        $totalAdmins = User::count();
        
        // Blocked IPs count
        $blockedIps = BlockedClient::activeBlocks()->count();
        
        // Calculate growth percentages (compare with previous period)
        $articlesThisMonth = Article::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $articlesLastMonth = Article::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $articleGrowth = $articlesLastMonth > 0 
            ? round((($articlesThisMonth - $articlesLastMonth) / $articlesLastMonth) * 100) 
            : 0;
        
        // Views growth
        $viewsThisMonth = Article::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('views');
        $viewsLastMonth = Article::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('views');
        $viewsGrowth = $viewsLastMonth > 0 
            ? round((($viewsThisMonth - $viewsLastMonth) / $viewsLastMonth) * 100) 
            : 0;
        
        // Stats array
        $stats = [
            'total_articles' => $totalArticles,
            'published_articles' => $publishedArticles,
            'draft_articles' => $draftArticles,
            'pending_articles' => $pendingArticles,
            'total_views' => $formattedViews,
            'total_views_raw' => $totalViews,
            'active_admins' => $activeAdmins,
            'total_admins' => $totalAdmins,
            'blocked_ips' => $blockedIps,
            'article_growth' => $articleGrowth,
            'views_growth' => $viewsGrowth,
        ];

        // Recent articles from database
        $recentArticles = Article::with(['author', 'categoryRelation'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Recent activity logs from database
        $activityLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Category distribution for chart
        $categories = Category::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(5)
            ->get();
        
        // Calculate category percentages
        $totalCategoryArticles = $categories->sum('articles_count');
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

        // Visit statistics for the last 7 days (using article views as proxy)
        // In a real app, you'd have a separate page_views or analytics table
        $visitStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $this->getDayName($date->dayOfWeek);
            
            // Get articles created/updated on this day and their views
            $dayViews = Article::whereDate('updated_at', $date->toDateString())
                ->sum('views');
            
            $visitStats[] = [
                'day' => $dayName,
                'date' => $date->format('d M'),
                'views' => $dayViews,
            ];
        }
        
        // Normalize visit stats for chart height (as percentage of max)
        $maxViews = max(array_column($visitStats, 'views')) ?: 1;
        $visitStats = array_map(function ($stat) use ($maxViews) {
            $stat['percentage'] = round(($stat['views'] / $maxViews) * 100);
            $stat['percentage'] = max(5, $stat['percentage']); // Minimum 5% height
            return $stat;
        }, $visitStats);

        // Security score calculation
        $securityScore = $this->calculateSecurityScore($blockedIps);

        return view('dashboard', compact(
            'stats',
            'recentArticles',
            'activityLogs',
            'categoryData',
            'visitStats',
            'totalCategoryArticles',
            'securityScore'
        ));
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
}
