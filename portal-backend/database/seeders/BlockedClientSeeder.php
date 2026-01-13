<?php

namespace Database\Seeders;

use App\Models\BlockedClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BlockedClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy data that looks realistic
        $data = [
            [
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'attempt_count' => 5,
                'is_blocked' => true,
                'blocked_until' => Carbon::now()->addHours(2),
                'reason' => 'Percobaan login berulang kali gagal',
                'blocked_route' => '/login',
                'created_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'ip_address' => '10.0.0.45',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
                'attempt_count' => 12,
                'is_blocked' => true,
                'blocked_until' => null, // Permanent
                'reason' => 'Terdeteksi scanner kerentanan (SQL Injection)',
                'blocked_route' => '/admin/dashboard',
                'created_at' => Carbon::now()->subHour(),
            ],
            [
                'ip_address' => '45.12.33.89',
                'user_agent' => 'python-requests/2.31.0',
                'attempt_count' => 50,
                'is_blocked' => true,
                'blocked_until' => Carbon::now()->addDays(1),
                'reason' => 'Rate limit exceeded (API Spam)',
                'blocked_route' => '/api/v1/articles',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'ip_address' => '202.14.77.21',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/115.0',
                'attempt_count' => 3,
                'is_blocked' => false,
                'blocked_until' => null,
                'reason' => null,
                'blocked_route' => '/search',
                'created_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'ip_address' => '172.16.0.22',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/120.0.6099.119 Mobile/15E148 Safari/604.1',
                'attempt_count' => 6,
                'is_blocked' => true,
                'blocked_until' => Carbon::now()->subMinutes(5), // Expired
                'reason' => 'Akses ilegal ke direktori terlarang',
                'blocked_route' => '/.env',
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'ip_address' => '66.249.66.1',
                'user_agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
                'attempt_count' => 15,
                'is_blocked' => true,
                'blocked_until' => Carbon::now()->addMinutes(30),
                'reason' => 'Crawling terlalu agresif',
                'blocked_route' => '/galleries',
                'created_at' => Carbon::now()->subMinutes(15),
            ],
            [
                'ip_address' => '192.168.100.50',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0',
                'attempt_count' => 4,
                'is_blocked' => false,
                'blocked_until' => null,
                'reason' => null,
                'blocked_route' => '/password/reset',
                'created_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'ip_address' => '114.125.88.99',
                'user_agent' => 'Unknown Agent',
                'attempt_count' => 8,
                'is_blocked' => true,
                'blocked_until' => Carbon::now()->addHours(12),
                'reason' => 'Percobaan brute force password',
                'blocked_route' => '/login',
                'created_at' => Carbon::now()->subHours(3),
            ],
        ];

        foreach ($data as $item) {
            BlockedClient::create($item);
        }
        
        // Add more random data
        for ($i = 0; $i < 15; $i++) {
            $isBlocked = rand(0, 100) > 30; // 70% chance blocked
            $createdAt = Carbon::now()->subDays(rand(0, 7))->subMinutes(rand(0, 1440));
            
            BlockedClient::create([
                'ip_address' => '10.10.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36',
                'attempt_count' => rand(1, 20),
                'is_blocked' => $isBlocked,
                'blocked_until' => $isBlocked ? (rand(0, 1) ? null : Carbon::now()->addMinutes(rand(10, 1000))) : null,
                'reason' => $isBlocked ? 'Suspicious activity detected #'.rand(1000,9999) : null,
                'blocked_route' => rand(0, 1) ? '/login' : '/api/v1/auth',
                'created_at' => $createdAt,
            ]);
        }
    }
}
