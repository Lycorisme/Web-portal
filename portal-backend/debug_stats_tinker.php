$visitStats = [];
for ($i = 6; $i >= 0; $i--) {
    $date = now()->subDays($i);
    $startOfDay = $date->copy()->startOfDay();
    $endOfDay = $date->copy()->endOfDay();
    
    $dayViews = \App\Models\ActivityLog::where('action', \App\Models\ActivityLog::ACTION_VIEW)
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->count();
    
    $visitStats[] = [
        'day' => $date->format('D'),
        'date' => $date->format('Y-m-d'),
        'views' => $dayViews,
    ];
}
print_r($visitStats);
