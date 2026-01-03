<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index()
    {
        // Real data from database
        $userCount = User::count();
        $articleCount = Article::count();
        $publishedArticles = Article::published()->count();
        $flaggedArticles = Article::rejected()->count();
        
        // Return real stats
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_readers' => $userCount,
                    'total_reader_change' => '+0',
                    'total_reader_change_type' => 'neutral',
                    'total_articles' => $publishedArticles,
                    'total_articles_change' => '+' . $articleCount . ' total',
                    'blocked_threats' => $flaggedArticles,
                    'blocked_threats_change' => $flaggedArticles > 0 ? 'Perlu review' : 'Aman',
                    'new_comments' => 0,
                    'new_comments_change' => '+0',
                ],
                'security_logs' => [],
                'chart_data' => [0, 0, 0, 0, 0, 0, 0]
            ]
        ]);
    }
}

