<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test user
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Sample activity logs
        $activities = [
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_LOGIN,
                'description' => 'Login ke sistem portal',
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'url' => 'http://localhost/login',
                'created_at' => now()->subMinutes(5),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_CREATE,
                'description' => 'Membuat artikel baru: "Pembangunan Infrastruktur 2026"',
                'subject_type' => 'App\\Models\\Article',
                'subject_id' => 1,
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/articles/create',
                'new_values' => ['title' => 'Pembangunan Infrastruktur 2026', 'status' => 'draft'],
                'created_at' => now()->subMinutes(10),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_UPDATE,
                'description' => 'Mengubah artikel: "Pembangunan Infrastruktur 2026"',
                'subject_type' => 'App\\Models\\Article',
                'subject_id' => 1,
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/articles/1/edit',
                'old_values' => ['status' => 'draft'],
                'new_values' => ['status' => 'published'],
                'created_at' => now()->subMinutes(15),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_SETTINGS_UPDATE,
                'description' => 'Mengubah pengaturan situs',
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/settings',
                'old_values' => ['site_name' => 'Portal Lama'],
                'new_values' => ['site_name' => 'Portal Berita BTIKP'],
                'created_at' => now()->subHours(1),
            ],
            [
                'user_id' => null,
                'action' => ActivityLog::ACTION_LOGIN_FAILED,
                'description' => 'Percobaan login gagal dengan email: unknown@example.com',
                'level' => ActivityLog::LEVEL_WARNING,
                'ip_address' => '10.0.0.55',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 10)',
                'url' => 'http://localhost/login',
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_DELETE,
                'description' => 'Menghapus artikel: "Berita Test"',
                'subject_type' => 'App\\Models\\Article',
                'subject_id' => 5,
                'level' => ActivityLog::LEVEL_DANGER,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/articles/5',
                'old_values' => ['title' => 'Berita Test', 'status' => 'draft'],
                'created_at' => now()->subHours(3),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_LOGOUT,
                'description' => 'Logout dari sistem',
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/logout',
                'created_at' => now()->subHours(4),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_EXPORT,
                'description' => 'Mengekspor data artikel ke PDF',
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/articles/export',
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_PASSWORD_CHANGE,
                'description' => 'Mengubah password akun',
                'level' => ActivityLog::LEVEL_WARNING,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/profile/password',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $user->id,
                'action' => ActivityLog::ACTION_CREATE,
                'description' => 'Membuat kategori baru: "Teknologi"',
                'subject_type' => 'App\\Models\\Category',
                'subject_id' => 1,
                'level' => ActivityLog::LEVEL_INFO,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/categories/create',
                'new_values' => ['name' => 'Teknologi', 'slug' => 'teknologi'],
                'created_at' => now()->subDays(3),
            ],
        ];

        // Add more random activities
        for ($i = 0; $i < 50; $i++) {
            $actions = [
                ActivityLog::ACTION_CREATE,
                ActivityLog::ACTION_UPDATE,
                ActivityLog::ACTION_VIEW,
                ActivityLog::ACTION_LOGIN,
                ActivityLog::ACTION_LOGOUT,
            ];
            
            $levels = [
                ActivityLog::LEVEL_INFO,
                ActivityLog::LEVEL_INFO,
                ActivityLog::LEVEL_INFO,
                ActivityLog::LEVEL_WARNING,
            ];

            $activities[] = [
                'user_id' => $user->id,
                'action' => $actions[array_rand($actions)],
                'description' => 'Aktivitas sistem - Log #' . ($i + 1),
                'level' => $levels[array_rand($levels)],
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'url' => 'http://localhost/dashboard',
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ];
        }

        foreach ($activities as $activity) {
            ActivityLog::create($activity);
        }

        $this->command->info('Activity logs seeded successfully!');
    }
}
