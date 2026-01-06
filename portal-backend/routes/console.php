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
    $time = SiteSetting::get('activity_log_cleanup_time', '00:00');
    $schedule = SiteSetting::get('activity_log_cleanup_schedule', 'daily');

    if ($schedule === 'daily') {
        Schedule::command('activity-log:cleanup')->dailyAt($time);
    } else {
        // Default fallback
        Schedule::command('activity-log:cleanup')->dailyAt($time);
    }
} catch (\Throwable $e) {
    // Fallback if database or table not accessible
    Schedule::command('activity-log:cleanup')->daily();
}
