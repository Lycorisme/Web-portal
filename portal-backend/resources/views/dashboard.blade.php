@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 animate-fade-in">
        {{-- Welcome Banner --}}
        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 p-6 shadow-xl shadow-theme-500/5 group">
            
            {{-- Abstract Art / Glows --}}
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-theme-500/10 rounded-full blur-3xl pointer-events-none opacity-50 mix-blend-screen animate-pulse-slow"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-theme-300/10 rounded-full blur-3xl pointer-events-none opacity-30 mix-blend-screen"></div>
            
            <!-- Grid Pattern Overlay -->
             <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000005_1px,transparent_1px),linear-gradient(to_bottom,#00000005_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:24px_24px] mask-image:linear-gradient(to_bottom,black,transparent) pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                
                {{-- Left Side: Text --}}
                <div class="flex-1 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-3">
                         <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-surface-100/80 dark:bg-surface-800/80 border border-surface-200 dark:border-surface-700 backdrop-blur-md">
                             <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[10px] uppercase tracking-wider font-bold text-surface-500 dark:text-surface-400">System Online</span>
                        </div>
                    </div>
                    
                    <h1 class="text-2xl lg:text-3xl font-bold text-surface-900 dark:text-white tracking-tight mb-2">
                        Welcome back, 
                        <span class="text-theme-600 dark:text-theme-400">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </span> 
                        <span class="inline-block animate-wave origin-bottom-right">👋</span>
                    </h1>
                    
                    <p class="text-sm text-surface-500 dark:text-surface-400 font-medium max-w-xl mx-auto md:mx-0">
                        @if($stats['draft_articles'] > 0)
                            You have <span class="text-surface-900 dark:text-white font-semibold">{{ $stats['draft_articles'] }} drafts</span> pending review.
                        @else
                            Everything is up to date. Have a productive day!
                        @endif
                    </p>

                    {{-- Compact Actions --}}
                    @if($stats['draft_articles'] > 0)
                    <div class="flex items-center justify-center md:justify-start gap-3 mt-5">
                        <a href="#" class="px-5 py-2.5 bg-surface-900 dark:bg-surface-800 text-white border border-surface-700 rounded-lg text-sm font-semibold hover:bg-surface-800 dark:hover:bg-surface-700 transition-colors flex items-center gap-2">
                            <i data-lucide="file-check" class="w-4 h-4 text-theme-400"></i>
                            <span>Review Draft</span>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Right Side: Clock Widget (From Settings) --}}
                <div class="hidden md:flex items-center gap-4 px-5 py-2.5 bg-white/50 dark:bg-surface-800/50 backdrop-blur-md border border-surface-200/60 dark:border-surface-700/60 rounded-2xl shadow-lg shadow-surface-200/10 dark:shadow-surface-900/10 hover:shadow-xl hover:scale-[1.02] hover:bg-white dark:hover:bg-surface-800 hover:border-theme-500/20 dark:hover:border-theme-500/20 transition-all duration-300 group/clock"
                     x-data="{
                        timestamp: {{ now()->timestamp * 1000 }},
                        hours: '00',
                        minutes: '00',
                        seconds: '00',
                        dayName: '',
                        fullDate: '',
                        init() {
                            this.update();
                            setInterval(() => {
                                this.timestamp += 1000;
                                this.update();
                            }, 1000);
                        },
                        update() {
                            const date = new Date(this.timestamp);
                            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            
                            this.dayName = days[date.getDay()];
                            this.fullDate = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
                            this.hours = String(date.getHours()).padStart(2, '0');
                            this.minutes = String(date.getMinutes()).padStart(2, '0');
                            this.seconds = String(date.getSeconds()).padStart(2, '0');
                        }
                     }"
                     x-cloak>
                    
                    {{-- Animated Icon --}}
                    <div class="relative">
                        <div class="absolute inset-0 bg-theme-500 rounded-full blur opacity-0 group-hover/clock:opacity-20 transition-opacity duration-500"></div>
                        <div class="relative p-2.5 bg-gradient-to-br from-theme-50 to-theme-100 dark:from-theme-900/40 dark:to-theme-800/40 rounded-xl text-theme-600 dark:text-theme-400 group-hover/clock:rotate-12 transition-transform duration-500">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="h-8 w-px bg-surface-200 dark:bg-surface-700/50"></div>

                    {{-- Time Display --}}
                    <div class="flex flex-col">
                        <div class="flex items-baseline gap-0.5">
                             <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="hours"></span>
                             <span class="text-theme-500 font-bold animate-pulse px-0.5">:</span>
                             <span class="text-xl font-bold font-space text-surface-900 dark:text-white tracking-tight" x-text="minutes"></span>
                             <span class="text-surface-400 font-bold px-0.5">:</span>
                             <span class="text-base font-medium font-space text-surface-500 dark:text-surface-400" x-text="seconds"></span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs font-medium text-surface-500 dark:text-surface-400">
                            <span x-text="dayName" class="text-theme-600 dark:text-theme-400"></span>
                            <span class="w-1 h-1 rounded-full bg-surface-300 dark:bg-surface-600"></span>
                            <span x-text="fullDate"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Key Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Total Berita --}}
            <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-primary-500/50 dark:hover:border-primary-500/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-primary-500/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="newspaper" class="w-16 h-16 text-primary-500 transform translate-x-4 -translate-y-4"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Total Berita</p>
                    <div class="flex items-end justify-between gap-2">
                        <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ number_format($stats['total_articles']) }}</h3>
                        <span class="inline-flex items-center gap-x-1 text-xs font-semibold {{ $stats['article_growth'] >= 0 ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10' : 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10' }} px-2 py-1 rounded-full mb-1">
                            <i data-lucide="{{ $stats['article_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                            {{ abs($stats['article_growth']) }}%
                        </span>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
                    <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-500 rounded-full" style="width: {{ $stats['total_articles'] > 0 ? ($stats['published_articles'] / $stats['total_articles']) * 100 : 0 }}%;"></div>
                    </div>
                    <span>{{ $stats['published_articles'] }} Pub</span>
                </div>
            </div>

            {{-- Total Views --}}
            <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-cyan/50 dark:hover:border-accent-cyan/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-cyan/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="eye" class="w-16 h-16 text-accent-cyan transform translate-x-4 -translate-y-4"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Total Views</p>
                    <div class="flex items-end justify-between gap-2">
                        <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ number_format($stats['total_views']) }}</h3>
                        <span class="inline-flex items-center gap-x-1 text-xs font-semibold {{ $stats['views_growth'] >= 0 ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10' : 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10' }} px-2 py-1 rounded-full mb-1">
                            <i data-lucide="{{ $stats['views_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                            {{ abs($stats['views_growth']) }}%
                        </span>
                    </div>
                </div>
                 <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
                    <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-accent-cyan rounded-full" style="width: 75%"></div>
                    </div>
                    <span>{{ date('M Y') }}</span>
                </div>
            </div>

            {{-- Admin Aktif --}}
            <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-violet/50 dark:hover:border-accent-violet/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-violet/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="users" class="w-16 h-16 text-accent-violet transform translate-x-4 -translate-y-4"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Admin Aktif</p>
                    <div class="flex items-end justify-between gap-2">
                         <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ $stats['active_admins'] }}</h3>
                        <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                            Online
                        </span>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
                    <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-accent-violet rounded-full" style="width: {{ $stats['total_admins'] > 0 ? ($stats['active_admins'] / $stats['total_admins']) * 100 : 0 }}%;"></div>
                    </div>
                    <span>{{ $stats['total_admins'] }} Total</span>
                </div>
            </div>

            {{-- Skor Keamanan --}}
            <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-amber/50 dark:hover:border-accent-amber/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-amber/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="shield-check" class="w-16 h-16 text-accent-amber transform translate-x-4 -translate-y-4"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Skor Keamanan</p>
                    <div class="flex items-end justify-between gap-2">
                        <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ $securityScore }}%</h3>
                        @if($stats['blocked_ips'] > 0)
                        <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded-full mb-1">
                            {{ $stats['blocked_ips'] }} BLK
                        </span>
                        @else
                        <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                            Aman
                        </span>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
                    <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                        <div class="h-full bg-accent-amber rounded-full" style="width: {{ $securityScore }}%;"></div>
                    </div>
                    <span>Good</span>
                </div>
            </div>
        </div>

        {{-- Main Content & Widgets Split --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Main Content Column (Left - 8 cols) --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                {{-- Data Visualization Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Visitor Charts --}}
                    <div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-surface-900 dark:text-white">Statistik</h3>
                                <p class="text-xs text-surface-500 dark:text-surface-400">7 hari terakhir</p>
                            </div>
                            <div class="p-2 bg-surface-50 dark:bg-surface-800 rounded-lg">
                                <i data-lucide="bar-chart-2" class="w-4 h-4 text-surface-400"></i>
                            </div>
                        </div>
                        <div class="h-48 flex items-end justify-between gap-2">
                            @foreach($visitStats as $index => $stat)
                            <div class="flex-1 flex flex-col items-center gap-1 group">
                                <div class="relative w-full rounded-t-lg bg-surface-100 dark:bg-surface-800 h-full flex items-end overflow-hidden">
                                     <div class="w-full bg-theme-500/80 group-hover:bg-theme-500 transition-all duration-300 relative rounded-t-sm" style="height: {{ $stat['percentage'] }}%;">
                                        <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-surface-900 text-white text-[10px] px-2 py-0.5 rounded transition-opacity whitespace-nowrap z-10">
                                            {{ number_format($stat['views']) }}
                                        </div>
                                     </div>
                                </div>
                                <span class="text-[10px] font-medium text-surface-400 uppercase">{{ substr($stat['day'], 0, 3) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Category Charts --}}
                    <div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm flex flex-col">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-surface-900 dark:text-white">Distribusi</h3>
                                <p class="text-xs text-surface-500 dark:text-surface-400">Kategori Berita</p>
                            </div>
                            <a href="#" class="text-xs text-primary-600 hover:underline">Detail</a>
                        </div>
                        
                        <div class="flex-1 flex items-center gap-6">
                            {{-- Circular Chart --}}
                            <div class="relative w-32 h-32 flex-shrink-0">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" fill="none" class="stroke-surface-100 dark:stroke-surface-800" stroke-width="12"></circle>
                                    @php $catOffset = 0; @endphp
                                    @foreach($categoryData as $index => $category)
                                        @if($category['count'] > 0)
                                            @php
                                                $catCircum = 2 * M_PI * 40;
                                                $catPct = $totalCategoryArticles > 0 ? ($category['count'] / $totalCategoryArticles) * 100 : 0;
                                                $catDash = ($catCircum * $catPct) / 100;
                                                $catGap = $catCircum - $catDash;
                                                $catDashOffset = -($catCircum * $catOffset) / 100;
                                                $catOffset += $catPct;
                                                // Colors matching theme
                                                $fillColors = ['#6366f1', '#06b6d4', '#f59e0b', '#ec4899', '#10b981']; 
                                                $strokeColor = $fillColors[$index % 5];
                                            @endphp
                                            <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $strokeColor }}"
                                                stroke-width="12" 
                                                stroke-dasharray="{{ $catDash }} {{ $catGap }}" 
                                                stroke-dashoffset="{{ $catDashOffset }}"
                                                stroke-linecap="butt" 
                                                class="transition-all duration-500 hover:opacity-80"></circle>
                                        @endif
                                    @endforeach
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-xl font-bold text-surface-900 dark:text-white">{{ $stats['total_articles'] }}</span>
                                </div>
                            </div>
                            
                            {{-- Legend --}}
                            <div class="flex-1 grid grid-cols-1 gap-1.5 overflow-hidden">
                                @forelse($categoryData->take(4) as $index => $category)
                                    @php $fillColors = ['bg-primary-500', 'bg-accent-cyan', 'bg-accent-amber', 'bg-pink-500', 'bg-emerald-500']; @endphp
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="w-2 h-2 rounded-full {{ $fillColors[$index % 5] }}"></span>
                                            <span class="text-surface-600 dark:text-surface-400 truncate max-w-[80px]">{{ $category['name'] }}</span>
                                        </div>
                                        <span class="font-semibold text-surface-900 dark:text-white">{{ $category['count'] }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-surface-400 italic">Belum ada data</p>
                                @endforelse
                                @if($categoryData->count() > 4)
                                    <p class="text-[10px] text-surface-400 mt-1">+{{ $categoryData->count() - 4 }} lainnya</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent News Section --}}
                <div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-surface-200 dark:border-surface-800 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-lg text-surface-900 dark:text-white">Berita Terbaru</h2>
                            <p class="text-sm text-surface-500 dark:text-surface-400">Update publikasi terakhir</p>
                        </div>
                        <a href="#" class="group inline-flex items-center gap-1 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700">
                            Semua Berita
                            <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    <div class="divide-y divide-surface-100 dark:divide-surface-800/50">
                        @forelse($recentArticles as $article)
                        <div class="group p-4 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors flex gap-4 items-start">
                             {{-- Thumbnail --}}
                             <div class="shrink-0 w-20 h-16 sm:w-24 sm:h-20 bg-surface-100 dark:bg-surface-800 rounded-lg overflow-hidden relative">
                                @if($article->thumbnail)
                                    <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-surface-300">
                                        <i data-lucide="image" class="w-8 h-8"></i>
                                    </div>
                                @endif
                                @if($article->status === 'published')
                                    <div class="absolute bottom-1 right-1 bg-black/50 backdrop-blur-sm text-white text-[10px] px-1.5 py-0.5 rounded">
                                        <i data-lucide="eye" class="w-3 h-3 inline-block -mt-0.5"></i> {{ $article->views }}
                                    </div>
                                @endif
                             </div>

                             {{-- Content --}}
                             <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-sm sm:text-base font-semibold text-surface-900 dark:text-white line-clamp-2 leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $article->title }}
                                    </h3>
                                    {{-- Status Badge --}}
                                    @php
                                        $badgeClass = match($article->status) {
                                            'published' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'draft' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                            'pending' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                            default => 'bg-surface-100 text-surface-600'
                                        };
                                    @endphp
                                    <span class="shrink-0 text-[10px] font-bold uppercase px-2 py-1 rounded {{ $badgeClass }}">
                                        {{ $article->status }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400 mt-1 line-clamp-1">
                                     {{ Str::limit(strip_tags($article->content), 80) }}
                                </p>
                                <div class="flex items-center gap-3 mt-2.5 text-xs text-surface-400">
                                    <span class="flex items-center gap-1">{{ $article->author->name ?? 'Admin' }}</span>
                                    <span class="w-1 h-1 bg-surface-300 rounded-full"></span>
                                    <span>{{ $article->updated_at->diffForHumans() }}</span>
                                </div>
                             </div>
                        </div>
                        @empty
                        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                            <div class="w-20 h-20 bg-surface-50 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="file-plus" class="w-10 h-10 text-surface-300 dark:text-surface-600"></i>
                            </div>
                            <h3 class="text-surface-900 dark:text-white font-medium mb-1">Belum Ada Berita</h3>
                            <p class="text-sm text-surface-500 dark:text-surface-400 mb-5 max-w-xs mx-auto">
                                Mulai publikasi konten pertama Anda untuk mengisi dashboard ini.
                            </p>
                            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Buat Berita Sekarang
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column (Widgets - 4 cols) --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                {{-- Quick Actions --}}
                <div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm">
                    <h3 class="font-bold text-surface-900 dark:text-white mb-4">Aksi Cepat</h3>
                     <div class="grid grid-cols-2 gap-3">
                        <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i data-lucide="pen-tool" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Tulis Berita</span>
                        </a>
                        <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i data-lucide="image" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Galeri</span>
                        </a>
                        <a href="{{ route('settings') }}" wire:navigate class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Pengaturan</span>
                        </a>
                        <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
                             <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Laporan</span>
                        </a>
                    </div>
                </div>

                {{-- Security Widget --}}
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden group">
                     <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i data-lucide="shield" class="w-24 h-24 transform translate-x-8 -translate-y-8"></i>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-4">
                         <div class="w-10 h-10 rounded-lg {{ $securityScore >= 80 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }} flex items-center justify-center backdrop-blur-sm">
                             <i data-lucide="{{ $securityScore >= 80 ? 'lock' : 'alert-triangle' }}" class="w-5 h-5"></i>
                         </div>
                         <div>
                             <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Keamanan</p>
                             <h4 class="font-bold text-lg">{{ $securityScore >= 80 ? 'Sistem Aman' : 'Perlu Tindakan' }}</h4>
                         </div>
                    </div>

                    <div class="space-y-3 relative z-10">
                        <div class="flex items-center justify-between text-sm border-b border-white/10 pb-2">
                             <span class="text-slate-300">Blocked IP</span>
                             <span class="font-mono {{ $stats['blocked_ips'] > 0 ? 'text-amber-400' : 'text-emerald-400' }}">{{ $stats['blocked_ips'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm border-b border-white/10 pb-2">
                             <span class="text-slate-300">Failed Logins</span>
                             <span class="font-mono text-slate-200">0</span>
                        </div>
                         <div class="flex items-center justify-between text-sm">
                             <span class="text-slate-300">Firewall</span>
                             <span class="text-emerald-400 font-bold text-xs uppercase">Active</span>
                        </div>
                    </div>
                </div>

                {{-- Activity Log --}}
                <div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-surface-900 dark:text-white">Aktivitas</h3>
                         <a href="{{ route('activity-log') }}" wire:navigate class="p-1 hover:bg-surface-100 dark:hover:bg-surface-800 rounded transition-colors" title="Lihat Semua">
                            <i data-lucide="history" class="w-4 h-4 text-surface-400"></i>
                         </a>
                    </div>
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($activityLogs as $log)
                        <div class="flex gap-3 relative pl-2">
                            <div class="absolute left-0 top-1.5 w-1.5 h-1.5 rounded-full {{ $loop->first ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></div>
                            <div class="flex-1">
                                <p class="text-xs text-surface-500 dark:text-surface-400 mb-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                <p class="text-sm text-surface-800 dark:text-surface-200 leading-snug">
                                    <span class="font-semibold">{{ $log->user->name ?? 'System' }}</span>
                                    {{ $log->description }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-sm text-surface-400 py-4">Tidak ada aktivitas baru.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
