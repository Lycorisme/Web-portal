@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-primary-700 to-accent-violet p-6 lg:p-8 mb-8 animate-fade-in">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/4 w-32 h-32 bg-accent-cyan/20 rounded-full blur-2xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                    <span class="w-2 h-2 bg-accent-emerald rounded-full animate-pulse"></span>
                    <span class="text-sm text-white/90 font-medium">Sistem Berjalan Normal</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white mb-2">Selamat Datang Kembali, {{ Auth::user()->name ?? 'Admin' }}! 👋</h1>
                <p class="text-white/80 text-sm lg:text-base max-w-xl">
                    Portal Berita BTIKP siap untuk dikelola. 
                    @if($stats['draft_articles'] > 0)
                        Ada {{ $stats['draft_articles'] }} berita draft yang menunggu untuk dipublikasi.
                    @else
                        Semua berita sudah dipublikasi.
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="#" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-primary-700 rounded-xl font-semibold hover:bg-white/90 transition-all duration-200 shadow-lg shadow-black/10 hover:shadow-xl hover:-translate-y-0.5">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Tambah Berita</span>
                </a>
                @if($stats['draft_articles'] > 0)
                <a href="#" class="inline-flex items-center gap-2 px-5 py-3 bg-white/20 text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-200 backdrop-blur-sm">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>Lihat Draft ({{ $stats['draft_articles'] }})</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6 mb-8">
        {{-- Total Berita --}}
        <div class="group bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl p-6 border border-surface-200/50 dark:border-surface-800/50 hover:shadow-xl hover:shadow-primary-500/10 hover:-translate-y-1 transition-all duration-300 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="newspaper" class="w-7 h-7 text-white"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $stats['article_growth'] >= 0 ? 'text-accent-emerald bg-accent-emerald/10' : 'text-accent-rose bg-accent-rose/10' }} px-2 py-1 rounded-full">
                    <i data-lucide="{{ $stats['article_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                    {{ $stats['article_growth'] >= 0 ? '+' : '' }}{{ $stats['article_growth'] }}%
                </span>
            </div>
            <h3 class="text-3xl font-bold text-surface-900 dark:text-white mb-1">{{ number_format($stats['total_articles']) }}</h3>
            <p class="text-sm text-surface-500 dark:text-surface-400">Total Berita</p>
            <div class="mt-4 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                @php
                    $publishedPercent = $stats['total_articles'] > 0 ? ($stats['published_articles'] / $stats['total_articles']) * 100 : 0;
                @endphp
                <div class="h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full" style="width: {{ $publishedPercent }}%;"></div>
            </div>
            <p class="text-xs text-surface-400 mt-2">{{ $stats['published_articles'] }} dipublikasi</p>
        </div>

        {{-- Total Views --}}
        <div class="group bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl p-6 border border-surface-200/50 dark:border-surface-800/50 hover:shadow-xl hover:shadow-accent-cyan/10 hover:-translate-y-1 transition-all duration-300 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-cyan to-accent-emerald flex items-center justify-center shadow-lg shadow-accent-cyan/30 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="eye" class="w-7 h-7 text-white"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $stats['views_growth'] >= 0 ? 'text-accent-emerald bg-accent-emerald/10' : 'text-accent-rose bg-accent-rose/10' }} px-2 py-1 rounded-full">
                    <i data-lucide="{{ $stats['views_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                    {{ $stats['views_growth'] >= 0 ? '+' : '' }}{{ $stats['views_growth'] }}%
                </span>
            </div>
            <h3 class="text-3xl font-bold text-surface-900 dark:text-white mb-1">{{ $stats['total_views'] }}</h3>
            <p class="text-sm text-surface-500 dark:text-surface-400">Total Views</p>
            <div class="mt-4 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <div class="h-full w-4/5 bg-gradient-to-r from-accent-cyan to-accent-emerald rounded-full"></div>
            </div>
        </div>

        {{-- Admin Aktif --}}
        <div class="group bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl p-6 border border-surface-200/50 dark:border-surface-800/50 hover:shadow-xl hover:shadow-accent-violet/10 hover:-translate-y-1 transition-all duration-300 animate-slide-up" style="animation-delay: 0.3s;">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-violet to-primary-600 flex items-center justify-center shadow-lg shadow-accent-violet/30 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="users" class="w-7 h-7 text-white"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-accent-emerald bg-accent-emerald/10 px-2 py-1 rounded-full">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    Online
                </span>
            </div>
            <h3 class="text-3xl font-bold text-surface-900 dark:text-white mb-1">{{ $stats['active_admins'] }}</h3>
            <p class="text-sm text-surface-500 dark:text-surface-400">Admin Aktif (30 hari)</p>
            <div class="mt-4 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                @php
                    $adminPercent = $stats['total_admins'] > 0 ? ($stats['active_admins'] / $stats['total_admins']) * 100 : 0;
                @endphp
                <div class="h-full bg-gradient-to-r from-accent-violet to-primary-500 rounded-full" style="width: {{ $adminPercent }}%;"></div>
            </div>
            <p class="text-xs text-surface-400 mt-2">dari {{ $stats['total_admins'] }} total admin</p>
        </div>

        {{-- Skor Keamanan --}}
        <div class="group bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl p-6 border border-surface-200/50 dark:border-surface-800/50 hover:shadow-xl hover:shadow-accent-amber/10 hover:-translate-y-1 transition-all duration-300 animate-slide-up" style="animation-delay: 0.4s;">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-amber to-accent-rose flex items-center justify-center shadow-lg shadow-accent-amber/30 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="shield-check" class="w-7 h-7 text-white"></i>
                </div>
                @if($stats['blocked_ips'] > 0)
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-accent-amber bg-accent-amber/10 px-2 py-1 rounded-full">
                    <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                    {{ $stats['blocked_ips'] }} Blocked
                </span>
                @else
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-accent-emerald bg-accent-emerald/10 px-2 py-1 rounded-full">
                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                    Aman
                </span>
                @endif
            </div>
            <h3 class="text-3xl font-bold text-surface-900 dark:text-white mb-1">{{ $securityScore }}%</h3>
            <p class="text-sm text-surface-500 dark:text-surface-400">Skor Keamanan</p>
            <div class="mt-4 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-accent-amber to-accent-rose rounded-full" style="width: {{ $securityScore }}%;"></div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
        {{-- Recent Articles Section --}}
        <div class="xl:col-span-2 bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden animate-slide-up" style="animation-delay: 0.5s;">
            <div class="p-6 border-b border-surface-200/50 dark:border-surface-800/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-surface-900 dark:text-white">Berita Terbaru</h2>
                    <p class="text-sm text-surface-500 dark:text-surface-400">Daftar berita yang baru diperbarui</p>
                </div>
                <a href="#" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">
                    Lihat Semua
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div class="divide-y divide-surface-100 dark:divide-surface-800/50">
                @forelse($recentArticles as $article)
                {{-- Article Item --}}
                <div class="p-4 lg:p-6 hover:bg-surface-50 dark:hover:bg-surface-800/30 transition-colors group">
                    <div class="flex gap-4">
                        <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/50 flex-shrink-0 overflow-hidden">
                            @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-primary-400"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    @php
                                        $statusColors = [
                                            'published' => 'bg-accent-emerald/10 text-accent-emerald',
                                            'draft' => 'bg-accent-amber/10 text-accent-amber',
                                            'pending' => 'bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400',
                                            'rejected' => 'bg-accent-rose/10 text-accent-rose',
                                        ];
                                        $statusLabels = [
                                            'published' => 'Published',
                                            'draft' => 'Draft',
                                            'pending' => 'Pending',
                                            'rejected' => 'Rejected',
                                        ];
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium {{ $statusColors[$article->status] ?? 'bg-surface-100 text-surface-600' }} rounded-full mb-2">
                                        {{ $statusLabels[$article->status] ?? ucfirst($article->status) }}
                                    </span>
                                    <h3 class="font-semibold text-surface-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                        {{ $article->title }}
                                    </h3>
                                </div>
                                <button class="p-2 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors opacity-0 group-hover:opacity-100">
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-surface-400"></i>
                                </button>
                            </div>
                            <p class="text-sm text-surface-500 dark:text-surface-400 mt-1 line-clamp-2">
                                {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}
                            </p>
                            <div class="flex items-center gap-4 mt-3 text-xs text-surface-400">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                    {{ $article->author->name ?? 'Unknown' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    {{ $article->updated_at->format('d M Y') }}
                                </span>
                                @if($article->status === 'published')
                                <span class="flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    {{ number_format($article->views) }} views
                                </span>
                                @else
                                <span class="flex items-center gap-1">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    {{ ucfirst($article->status) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-8 h-8 text-surface-400"></i>
                    </div>
                    <h3 class="font-medium text-surface-900 dark:text-white mb-1">Belum Ada Berita</h3>
                    <p class="text-sm text-surface-500 dark:text-surface-400">Mulai dengan membuat berita pertama Anda</p>
                    <a href="#" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Buat Berita
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar Widgets --}}
        <div class="space-y-6">
            {{-- Activity Log --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden animate-slide-up" style="animation-delay: 0.6s;">
                <div class="p-6 border-b border-surface-200/50 dark:border-surface-800/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-surface-900 dark:text-white">Activity Log</h2>
                        <p class="text-sm text-surface-500 dark:text-surface-400">Aktivitas terbaru</p>
                    </div>
                    <a href="#" class="p-2 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                        <i data-lucide="external-link" class="w-4 h-4 text-surface-400"></i>
                    </a>
                </div>
                <div class="p-4 space-y-4 max-h-80 overflow-y-auto">
                    @forelse($activityLogs as $log)
                    <div class="flex gap-3">
                        <div class="relative">
                            @php
                                $iconColors = [
                                    'CREATE' => 'bg-accent-emerald/10 text-accent-emerald',
                                    'UPDATE' => 'bg-primary-100 dark:bg-primary-900/30 text-primary-600',
                                    'DELETE' => 'bg-accent-rose/10 text-accent-rose',
                                    'LOGIN' => 'bg-accent-emerald/10 text-accent-emerald',
                                    'LOGIN_FAILED' => 'bg-accent-amber/10 text-accent-amber',
                                    'LOGOUT' => 'bg-surface-100 dark:bg-surface-800 text-surface-500',
                                    'SETTINGS_UPDATE' => 'bg-primary-100 dark:bg-primary-900/30 text-primary-600',
                                ];
                                $icons = [
                                    'CREATE' => 'plus-circle',
                                    'UPDATE' => 'edit',
                                    'DELETE' => 'trash-2',
                                    'LOGIN' => 'log-in',
                                    'LOGIN_FAILED' => 'shield-alert',
                                    'LOGOUT' => 'log-out',
                                    'SETTINGS_UPDATE' => 'settings',
                                ];
                                $colorClass = $iconColors[$log->action] ?? 'bg-surface-100 dark:bg-surface-800 text-surface-500';
                                $icon = $icons[$log->action] ?? 'activity';
                            @endphp
                            <div class="w-10 h-10 rounded-xl {{ $colorClass }} flex items-center justify-center">
                                <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                            </div>
                            @if(!$loop->last)
                            <div class="absolute top-10 left-1/2 w-0.5 h-full -translate-x-1/2 bg-surface-200 dark:bg-surface-700"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-sm text-surface-700 dark:text-surface-300">
                                <span class="font-semibold">{{ $log->user->name ?? 'System' }}</span>
                                {{ $log->description }}
                            </p>
                            <p class="text-xs text-surface-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-sm text-surface-500 dark:text-surface-400">Belum ada aktivitas</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-6 animate-slide-up" style="animation-delay: 0.7s;">
                <h2 class="text-lg font-bold text-surface-900 dark:text-white mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" onclick="showToast('info', 'Info', 'Membuka form berita baru...')"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/20 hover:shadow-lg hover:shadow-primary-500/10 hover:-translate-y-0.5 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-xl bg-primary-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="plus" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-primary-700 dark:text-primary-300">Berita Baru</span>
                    </a>
                    <a href="#" onclick="showToast('info', 'Info', 'Membuka galeri media...')"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl bg-gradient-to-br from-accent-cyan/10 to-accent-emerald/10 dark:from-accent-cyan/10 dark:to-accent-emerald/5 hover:shadow-lg hover:shadow-accent-cyan/10 hover:-translate-y-0.5 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-cyan to-accent-emerald flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="upload" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-accent-cyan dark:text-accent-cyan">Upload Galeri</span>
                    </a>
                    <a href="#" onclick="showToast('success', 'Export', 'Export PDF sedang diproses...')"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl bg-gradient-to-br from-accent-violet/10 to-primary-100/50 dark:from-accent-violet/10 dark:to-primary-900/20 hover:shadow-lg hover:shadow-accent-violet/10 hover:-translate-y-0.5 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-violet to-primary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="file-down" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-accent-violet dark:text-accent-violet">Export PDF</span>
                    </a>
                    <a href="#"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl bg-gradient-to-br from-accent-amber/10 to-accent-rose/10 dark:from-accent-amber/5 dark:to-accent-rose/5 hover:shadow-lg hover:shadow-accent-amber/10 hover:-translate-y-0.5 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-amber to-accent-rose flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="settings" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-accent-amber dark:text-accent-amber">Pengaturan</span>
                    </a>
                </div>
            </div>

            {{-- Security Status --}}
            <div class="bg-gradient-to-br from-surface-900 to-surface-800 dark:from-surface-800 dark:to-surface-900 rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.8s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl {{ $securityScore >= 90 ? 'bg-accent-emerald/20' : ($securityScore >= 70 ? 'bg-accent-amber/20' : 'bg-accent-rose/20') }} flex items-center justify-center">
                        <i data-lucide="{{ $securityScore >= 90 ? 'shield-check' : 'shield-alert' }}" class="w-6 h-6 {{ $securityScore >= 90 ? 'text-accent-emerald' : ($securityScore >= 70 ? 'text-accent-amber' : 'text-accent-rose') }}"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white">Status Keamanan</h3>
                        <p class="text-sm text-surface-400">
                            @if($securityScore >= 90)
                                Semua sistem aman
                            @elseif($securityScore >= 70)
                                Perlu perhatian
                            @else
                                Memerlukan tindakan
                            @endif
                        </p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-300">Rate Limiting</span>
                        <span class="flex items-center gap-1.5 text-xs font-medium text-accent-emerald">
                            <span class="w-2 h-2 bg-accent-emerald rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-300">Input Validation</span>
                        <span class="flex items-center gap-1.5 text-xs font-medium text-accent-emerald">
                            <span class="w-2 h-2 bg-accent-emerald rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-300">Activity Logging</span>
                        <span class="flex items-center gap-1.5 text-xs font-medium text-accent-emerald">
                            <span class="w-2 h-2 bg-accent-emerald rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-300">IP Terblokir</span>
                        <span class="flex items-center gap-1.5 text-xs font-medium {{ $stats['blocked_ips'] > 0 ? 'text-accent-amber' : 'text-accent-emerald' }}">
                            <span class="w-2 h-2 {{ $stats['blocked_ips'] > 0 ? 'bg-accent-amber' : 'bg-accent-emerald' }} rounded-full"></span>
                            {{ $stats['blocked_ips'] }} IP
                        </span>
                    </div>
                </div>
                <a href="#" class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-medium text-white transition-colors">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        {{-- Visitor Stats Chart --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-6 animate-slide-up" style="animation-delay: 0.9s;">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-surface-900 dark:text-white">Statistik Kunjungan</h2>
                    <p class="text-sm text-surface-500 dark:text-surface-400">7 hari terakhir</p>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between gap-2 px-2">
                @foreach($visitStats as $index => $stat)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-gradient-to-t {{ $index === count($visitStats) - 2 ? 'from-accent-cyan to-accent-emerald' : ($index === count($visitStats) - 1 ? 'from-accent-violet to-primary-500' : 'from-primary-500 to-primary-400') }} rounded-t-lg transition-all duration-500 hover:opacity-80" style="height: {{ $stat['percentage'] }}%;" title="{{ number_format($stat['views']) }} views"></div>
                    <span class="text-xs text-surface-500">{{ $stat['day'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Category Distribution Chart --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-6 animate-slide-up" style="animation-delay: 1s;">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-surface-900 dark:text-white">Distribusi Kategori</h2>
                    <p class="text-sm text-surface-500 dark:text-surface-400">Berdasarkan jumlah berita</p>
                </div>
            </div>
            <div class="flex items-center justify-center mb-6">
                <div class="relative w-48 h-48">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="12"
                            class="text-surface-100 dark:text-surface-800"></circle>
                        @php
                            $offset = 0;
                            $gradientColors = [
                                ['from' => '#6366f1', 'to' => '#8b5cf6'],
                                ['from' => '#06b6d4', 'to' => '#10b981'],
                                ['from' => '#f59e0b', 'to' => '#f43f5e'],
                                ['from' => '#8b5cf6', 'to' => '#ec4899'],
                                ['from' => '#10b981', 'to' => '#14b8a6'],
                            ];
                        @endphp
                        @foreach($categoryData as $index => $category)
                            @if($category['count'] > 0)
                                @php
                                    $circumference = 2 * M_PI * 40;
                                    $percentage = $totalCategoryArticles > 0 ? ($category['count'] / $totalCategoryArticles) * 100 : 0;
                                    $dashLength = ($circumference * $percentage) / 100;
                                    $gapLength = $circumference - $dashLength;
                                    $dashOffset = -($circumference * $offset) / 100;
                                    $offset += $percentage;
                                @endphp
                                <circle cx="50" cy="50" r="40" fill="none" stroke="url(#gradient{{ $index }})"
                                    stroke-width="12" 
                                    stroke-dasharray="{{ $dashLength }} {{ $gapLength }}" 
                                    stroke-dashoffset="{{ $dashOffset }}"
                                    stroke-linecap="round" 
                                    class="transition-all duration-1000"></circle>
                            @endif
                        @endforeach
                        <defs>
                            @foreach($gradientColors as $index => $colors)
                                <linearGradient id="gradient{{ $index }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="{{ $colors['from'] }}"></stop>
                                    <stop offset="100%" stop-color="{{ $colors['to'] }}"></stop>
                                </linearGradient>
                            @endforeach
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold text-surface-900 dark:text-white">{{ $stats['total_articles'] }}</span>
                        <span class="text-sm text-surface-500">Total</span>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $gradientClasses = [
                        'bg-gradient-to-r from-primary-500 to-accent-violet',
                        'bg-gradient-to-r from-accent-cyan to-accent-emerald',
                        'bg-gradient-to-r from-accent-amber to-accent-rose',
                        'bg-gradient-to-r from-accent-violet to-pink-500',
                        'bg-gradient-to-r from-accent-emerald to-teal-500',
                    ];
                @endphp
                @forelse($categoryData as $index => $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $gradientClasses[$index % count($gradientClasses)] }}"></span>
                        <span class="text-sm text-surface-600 dark:text-surface-400">{{ $category['name'] }}</span>
                    </div>
                    <span class="text-sm font-semibold text-surface-900 dark:text-white">{{ $category['count'] }} berita</span>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-sm text-surface-500 dark:text-surface-400">Belum ada kategori</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
