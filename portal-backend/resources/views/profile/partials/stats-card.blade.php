{{-- Quick Stats Card --}}
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6">
    <div class="flex items-center gap-3 mb-4 sm:mb-6">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-accent-cyan to-accent-emerald flex items-center justify-center shadow-lg shadow-accent-cyan/30">
            <i data-lucide="bar-chart-3" class="w-5 h-5 sm:w-6 sm:h-6 text-white"></i>
        </div>
        <div>
            <h3 class="font-bold text-surface-900 dark:text-white">Statistik</h3>
            <p class="text-xs text-surface-500 dark:text-surface-400">Ringkasan aktivitas Anda</p>
        </div>
    </div>

    <div class="space-y-4">
        {{-- Articles Count --}}
        <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5 text-primary-600 dark:text-primary-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Artikel</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400">Total ditulis</p>
                </div>
            </div>
            <span class="text-xl font-bold text-surface-900 dark:text-white">{{ $stats['articles_count'] }}</span>
        </div>

        {{-- Total Views --}}
        <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-accent-cyan/20 flex items-center justify-center">
                    <i data-lucide="eye" class="w-5 h-5 text-accent-cyan"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Views</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400">Total pembaca</p>
                </div>
            </div>
            <span class="text-xl font-bold text-surface-900 dark:text-white">{{ number_format($stats['total_views']) }}</span>
        </div>

        {{-- Login Count --}}
        <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-accent-emerald/20 flex items-center justify-center">
                    <i data-lucide="log-in" class="w-5 h-5 text-accent-emerald"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Login</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400">Total sesi</p>
                </div>
            </div>
            <span class="text-xl font-bold text-surface-900 dark:text-white">{{ $stats['login_count'] }}</span>
        </div>

        {{-- Member Since --}}
        <div class="flex items-center justify-between p-3 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-accent-amber/20 flex items-center justify-center">
                    <i data-lucide="calendar" class="w-5 h-5 text-accent-amber"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Bergabung</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400">Member sejak</p>
                </div>
            </div>
            <span class="text-sm font-bold text-surface-900 dark:text-white">
                {{ $stats['member_since']->format('M Y') }}
            </span>
        </div>
    </div>
</div>
