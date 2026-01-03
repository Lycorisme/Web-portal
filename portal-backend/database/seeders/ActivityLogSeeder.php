<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Article;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found, skipping activity log seeder.');
            return;
        }

        $activities = [
            [
                'action' => ActivityLog::ACTION_LOGIN,
                'description' => 'berhasil login ke sistem',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subMinutes(5),
            ],
            [
                'action' => ActivityLog::ACTION_CREATE,
                'description' => 'menambahkan berita baru "Peringatan HUT BTIKP"',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'action' => ActivityLog::ACTION_UPDATE,
                'description' => 'mengubah pengaturan situs',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subHours(1),
            ],
            [
                'action' => ActivityLog::ACTION_LOGIN_FAILED,
                'description' => 'percobaan login gagal dari IP 192.168.1.45',
                'level' => ActivityLog::LEVEL_WARNING,
                'created_at' => now()->subHours(2),
            ],
            [
                'action' => ActivityLog::ACTION_DELETE,
                'description' => 'menghapus berita lama',
                'level' => ActivityLog::LEVEL_WARNING,
                'created_at' => now()->subHours(3),
            ],
            [
                'action' => ActivityLog::ACTION_UPDATE,
                'description' => 'memperbarui artikel "Workshop Keamanan Siber"',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subHours(5),
            ],
            [
                'action' => ActivityLog::ACTION_CREATE,
                'description' => 'membuat kategori baru "Teknologi"',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subHours(8),
            ],
            [
                'action' => ActivityLog::ACTION_SETTINGS_UPDATE,
                'description' => 'mengubah konfigurasi email',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subHours(12),
            ],
            [
                'action' => ActivityLog::ACTION_LOGIN,
                'description' => 'berhasil login ke sistem',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subDay(),
            ],
            [
                'action' => ActivityLog::ACTION_LOGOUT,
                'description' => 'logout dari sistem',
                'level' => ActivityLog::LEVEL_INFO,
                'created_at' => now()->subDay()->addHours(8),
            ],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create(array_merge($activity, [
                'user_id' => $user->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/dashboard',
            ]));
        }

        $this->command->info('Activity logs seeded successfully!');
    }
}
