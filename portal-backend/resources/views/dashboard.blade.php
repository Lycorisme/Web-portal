@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Title -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 font-serif">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau performa konten dan status keamanan sistem hari ini.</p>
        </div>
        <div class="hidden md:flex gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full flex items-center gap-1 border border-green-200">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> System Healthy
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @include('partials.stat-card', [
            'title' => 'Total Pembaca',
            'value' => '45.2K',
            'change' => '+12.5% hari ini',
            'changeType' => 'up',
            'icon' => 'fa-regular fa-eye',
            'iconBg' => 'bg-blue-50',
            'iconColor' => 'text-news-blue',
            'highlight' => false
        ])
        
        @include('partials.stat-card', [
            'title' => 'Artikel Terbit',
            'value' => '1,204',
            'change' => '5 artikel draft',
            'changeType' => 'neutral',
            'icon' => 'fa-regular fa-file-lines',
            'iconBg' => 'bg-purple-50',
            'iconColor' => 'text-purple-600',
            'highlight' => false
        ])
        
        @include('partials.stat-card', [
            'title' => 'Ancaman Diblokir',
            'value' => '28 IP',
            'change' => 'Rate Limit Active',
            'changeType' => 'down',
            'icon' => 'fa-solid fa-ban',
            'iconBg' => 'bg-red-50',
            'iconColor' => 'text-red-600',
            'highlight' => true
        ])
        
        @include('partials.stat-card', [
            'title' => 'Komentar Baru',
            'value' => '15',
            'change' => 'Perlu Moderasi',
            'changeType' => 'neutral',
            'icon' => 'fa-regular fa-comments',
            'iconBg' => 'bg-orange-50',
            'iconColor' => 'text-orange-500',
            'highlight' => false
        ])
    </div>

    <!-- Charts & Logs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Traffic Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-800">Analitik Trafik vs Serangan</h3>
                <select class="text-xs border-slate-200 rounded-md text-slate-500 px-3 py-1.5">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <!-- Security Logs -->
        <div class="bg-white p-0 rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-sm">Log Keamanan Terbaru</h3>
                <a href="#" class="text-xs text-news-blue hover:underline">Lihat Semua</a>
            </div>
            <div class="flex-1 overflow-y-auto max-h-[300px] p-2">
                @include('partials.security-log-item', ['type' => 'error', 'title' => 'Login Gagal (3x)', 'detail' => 'IP: 192.168.1.45', 'time' => '2 menit lalu'])
                @include('partials.security-log-item', ['type' => 'info', 'title' => 'Update Artikel: "Pembangunan IKN"', 'detail' => 'User: Editor', 'time' => '15 menit lalu'])
                @include('partials.security-log-item', ['type' => 'success', 'title' => 'User Login Berhasil', 'detail' => 'User: Super Admin', 'time' => '1 jam lalu'])
                @include('partials.security-log-item', ['type' => 'warning', 'title' => 'Komentar Spam Terdeteksi', 'detail' => 'System Purifier', 'time' => '3 jam lalu'])
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="font-bold text-slate-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <button 
                onclick="showToast('success', 'Berhasil', 'Artikel baru berhasil dibuat!')"
                class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm border border-slate-100 hover:shadow-md hover:border-news-blue/30 transition-all duration-150 group"
            >
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-news-blue group-hover:bg-news-blue group-hover:text-white transition-all duration-150">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <span class="font-medium text-slate-700">Buat Berita Baru</span>
            </button>
            
            <button 
                onclick="showToast('info', 'Info', 'Membuka galeri media...')"
                class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm border border-slate-100 hover:shadow-md hover:border-purple-300 transition-all duration-150 group"
            >
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-150">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <span class="font-medium text-slate-700">Upload Media</span>
            </button>
            
            <button 
                onclick="showAlert('warning', 'Peringatan', 'Apakah Anda yakin ingin menjalankan scan keamanan?', function() { showToast('success', 'Selesai', 'Scan keamanan selesai!'); })"
                class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm border border-slate-100 hover:shadow-md hover:border-green-300 transition-all duration-150 group"
            >
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all duration-150">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <span class="font-medium text-slate-700">Scan Keamanan</span>
            </button>
            
            <button 
                onclick="showToast('warning', 'Perhatian', 'Ada 5 komentar menunggu moderasi')"
                class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm border border-slate-100 hover:shadow-md hover:border-orange-300 transition-all duration-150 group"
            >
                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all duration-150">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <span class="font-medium text-slate-700">Moderasi Komentar</span>
            </button>
        </div>
    </div>

    <!-- Recent Articles -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-800">Artikel Terbaru</h3>
            <a href="#" class="text-sm text-news-blue hover:underline">Lihat Semua →</a>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Kategori</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Penulis</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Views</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @include('partials.article-row', [
                        'title' => 'Pembangunan IKN Rampung 80%',
                        'category' => 'Politik',
                        'author' => 'John Doe',
                        'status' => 'published',
                        'views' => '12.5K'
                    ])
                    @include('partials.article-row', [
                        'title' => 'IHSG Menguat di Akhir Pekan',
                        'category' => 'Ekonomi',
                        'author' => 'Jane Smith',
                        'status' => 'published',
                        'views' => '8.2K'
                    ])
                    @include('partials.article-row', [
                        'title' => 'Timnas U-23 Lolos ke Semifinal',
                        'category' => 'Olahraga',
                        'author' => 'Ahmad Rizki',
                        'status' => 'draft',
                        'views' => '—'
                    ])
                    @include('partials.article-row', [
                        'title' => 'Cuaca Ekstrem Landa Jakarta',
                        'category' => 'Nasional',
                        'author' => 'Sarah Lee',
                        'status' => 'published',
                        'views' => '15.3K'
                    ])
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trafficChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Traffic (Views)',
                        data: [1200, 1900, 3000, 5000, 2400, 3200, 4500],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Percobaan Serangan (Blocked)',
                        data: [5, 12, 8, 25, 4, 7, 10],
                        borderColor: '#dc2626',
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });

    // Toast helper function
    function showToast(type, title, message) {
        window.dispatchEvent(new CustomEvent('toast', { 
            detail: { type, title, message } 
        }));
    }

    // Alert helper function
    function showAlert(type, title, message, callback) {
        window.dispatchEvent(new CustomEvent('alert', { 
            detail: { type, title, message, callback } 
        }));
    }
</script>
@endpush
