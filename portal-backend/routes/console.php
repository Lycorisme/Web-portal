<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\SiteSetting;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

try {
    // Attempt to get schedule settings
    // Wrap in try-catch to prevent issues during migrations/setup if table missing
    $enabled = SiteSetting::get('activity_log_cleanup_enabled', false);
    
    if ($enabled) {
        $time = SiteSetting::get('activity_log_cleanup_time', '00:00');
        $schedule = SiteSetting::get('activity_log_cleanup_schedule', 'daily');

        $command = Schedule::command('activity-log:cleanup');

        switch ($schedule) {
            case 'weekly':
                // Run weekly on Sunday at the specified time
                $command->weeklyOn(0, $time);
                break;
            case 'monthly':
                // Run monthly on the 1st at the specified time
                $command->monthlyOn(1, $time);
                break;
            case 'daily':
            default:
                $command->dailyAt($time);
                break;
        }
    }
} catch (\Throwable $e) {
    // Fallback if database or table not accessible - don't schedule anything
    // to prevent errors during migrations
}
