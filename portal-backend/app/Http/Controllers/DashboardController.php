<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Dashboard statistics (can be fetched from database later)
        $stats = [
            'total_readers' => '45.2K',
            'total_articles' => 1204,
            'blocked_ips' => 28,
            'pending_comments' => 15,
        ];

        // Recent articles (placeholder data)
        $articles = [
            [
                'title' => 'Pembangunan IKN Rampung 80%',
                'category' => 'Politik',
                'author' => 'John Doe',
                'status' => 'published',
                'views' => '12.5K',
            ],
            [
                'title' => 'IHSG Menguat di Akhir Pekan',
                'category' => 'Ekonomi',
                'author' => 'Jane Smith',
                'status' => 'published',
                'views' => '8.2K',
            ],
            [
                'title' => 'Timnas U-23 Lolos ke Semifinal',
                'category' => 'Olahraga',
                'author' => 'Ahmad Rizki',
                'status' => 'draft',
                'views' => '—',
            ],
        ];

        // Security logs (placeholder data)
        $securityLogs = [
            [
                'type' => 'error',
                'title' => 'Login Gagal (3x)',
                'detail' => 'IP: 192.168.1.45',
                'time' => '2 menit lalu',
            ],
            [
                'type' => 'info',
                'title' => 'Update Artikel: "Pembangunan IKN"',
                'detail' => 'User: Editor',
                'time' => '15 menit lalu',
            ],
            [
                'type' => 'success',
                'title' => 'User Login Berhasil',
                'detail' => 'User: Super Admin',
                'time' => '1 jam lalu',
            ],
        ];

        return view('dashboard', compact('stats', 'articles', 'securityLogs'));
    }
}
