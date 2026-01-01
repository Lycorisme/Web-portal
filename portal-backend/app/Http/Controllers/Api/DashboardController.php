<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        
        // Return real stats where available, others as 0 (for now) until tables are created
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_readers' => $userCount, // Using user count as readers for now
                    'total_reader_change' => '+0', // No history data yet
                    'total_reader_change_type' => 'neutral',
                    'total_articles' => 0, // No articles table yet
                    'total_articles_change' => '+0',
                    'blocked_threats' => 0,
                    'blocked_threats_change' => '+0',
                    'new_comments' => 0,
                    'new_comments_change' => '+0',
                ],
                // Empty logs for now, but fetched from API
                'security_logs' => [],
                // Empty chart data for now
                'chart_data' => [0, 0, 0, 0, 0, 0, 0]
            ]
        ]);
    }
}
