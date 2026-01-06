<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use App\Models\SiteSetting;

class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-log:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete old activity logs based on retention settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enabled = SiteSetting::get('activity_log_cleanup_enabled', false);

        if (!$enabled) {
            $this->info('Activity log cleanup is disabled.');
            return;
        }

        $days = (int) SiteSetting::get('activity_log_retention_days', 30);
        $date = now()->subDays($days);

        $this->info("Cleaning up activity logs older than {$days} days (before {$date->toDateTimeString()})...");

        $count = ActivityLog::where('created_at', '<', $date)->forceDelete();

        $this->info("Deleted {$count} activity logs.");
    }
}
