<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default settings
        DB::table('site_settings')->insert([
            [
                'key' => 'activity_log_cleanup_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Auto Delete Activity Logs',
                'description' => 'Enable automatically deleting old activity logs.',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'activity_log_retention_days',
                'value' => '30',
                'type' => 'integer',
                'group' => 'system',
                'label' => 'Activity Log Retention (Days)',
                'description' => 'Logs older than this number of days will be permanently deleted.',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'activity_log_cleanup_schedule',
                'value' => 'daily', // Options: daily, weekly, monthly, or specific days
                'type' => 'string',
                'group' => 'system',
                'label' => 'Cleanup Schedule Frequency',
                'description' => 'How often the cleanup job runs.',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'activity_log_cleanup_time',
                'value' => '00:00',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Cleanup Schedule Time',
                'description' => 'The time of day to run the cleanup job.',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')
            ->whereIn('key', [
                'activity_log_cleanup_enabled', 
                'activity_log_retention_days',
                'activity_log_cleanup_schedule',
                'activity_log_cleanup_time'
            ])
            ->delete();
    }
};
