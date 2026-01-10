<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
    {{-- Total Berita / Berita Saya --}}
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-primary-500/50 dark:hover:border-primary-500/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-primary-500/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="newspaper" class="w-16 h-16 text-primary-500 transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">
                {{ $stats['is_author'] ? 'Berita Saya' : 'Total Berita' }}
            </p>
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

    {{-- Total Views / Views Berita Saya --}}
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-cyan/50 dark:hover:border-accent-cyan/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-cyan/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="eye" class="w-16 h-16 text-accent-cyan transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">
                {{ $stats['is_author'] ? 'Views Berita Saya' : 'Total Views' }}
            </p>
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

    {{-- Pending Review / Menunggu Persetujuan (Different context per role) --}}
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-amber/50 dark:hover:border-accent-amber/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-amber/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="{{ $stats['is_author'] ? 'clock' : 'inbox' }}" class="w-16 h-16 text-accent-amber transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">
                @if($stats['is_author'])
                    Menunggu Persetujuan
                @else
                    Antrean Review
                @endif
            </p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ $stats['pending_articles'] }}</h3>
                @if($stats['pending_articles'] > 0)
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                    {{ $stats['is_author'] ? 'Pending' : 'Perlu Aksi' }}
                </span>
                @else
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    {{ $stats['is_author'] ? 'Clear' : 'Kosong' }}
                </span>
                @endif
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                @php 
                    $pendingPercentage = $stats['total_articles'] > 0 ? ($stats['pending_articles'] / $stats['total_articles']) * 100 : 0;
                @endphp
                <div class="h-full bg-accent-amber rounded-full" style="width: {{ $pendingPercentage }}%;"></div>
            </div>
            <span>{{ $stats['is_author'] ? 'Progres' : 'Queue' }}</span>
        </div>
    </div>

    {{-- Admin: Skor Keamanan | Editor: Draft Articles | Author: Draft Saya --}}
    @if($stats['is_admin'])
    {{-- Admin: Security Score --}}
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-emerald-500/50 dark:hover:border-emerald-500/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-emerald-500/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="shield-check" class="w-16 h-16 text-emerald-500 transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Skor Keamanan</p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ $securityScore ?? 100 }}%</h3>
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
                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $securityScore ?? 100 }}%;"></div>
            </div>
            <span>Good</span>
        </div>
    </div>
    @elseif($stats['is_editor'])
    {{-- Editor: Active Team Members --}}
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-violet/50 dark:hover:border-accent-violet/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-violet/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="users" class="w-16 h-16 text-accent-violet transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Tim Aktif</p>
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
    @else
    {{-- Author: Draft Articles Count --}}
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-violet/50 dark:hover:border-accent-violet/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-violet/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="file-edit" class="w-16 h-16 text-accent-violet transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Draft Saya</p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white">{{ $stats['draft_articles'] }}</h3>
                @if($stats['draft_articles'] > 0)
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="edit-3" class="w-3 h-3"></i>
                    Lanjutkan
                </span>
                @else
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="check" class="w-3 h-3"></i>
                    Kosong
                </span>
                @endif
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                @php 
                    $draftPercentage = $stats['total_articles'] > 0 ? ($stats['draft_articles'] / $stats['total_articles']) * 100 : 0;
                @endphp
                <div class="h-full bg-accent-violet rounded-full" style="width: {{ $draftPercentage }}%;"></div>
            </div>
            <span>Perlu Diselesaikan</span>
        </div>
    </div>
    @endif
</div>

