{{-- Recent Activity Card --}}
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-amber to-orange-500 flex items-center justify-center shadow-lg shadow-accent-amber/30 flex-shrink-0">
                <i data-lucide="activity" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Aktivitas Terbaru</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Riwayat aktivitas akun Anda</p>
            </div>
        </div>
        <a href="{{ route('activity-log') }}" 
           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
            <span>Lihat Semua</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    @if($recentActivities->count() > 0)
    <div class="space-y-4">
        @foreach($recentActivities as $activity)
        <div class="flex items-start gap-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors duration-200">
            {{-- Activity Icon --}}
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                @switch($activity->action)
                    @case('create')
                        bg-accent-emerald/20
                        @break
                    @case('update')
                        bg-accent-cyan/20
                        @break
                    @case('delete')
                        bg-accent-rose/20
                        @break
                    @case('login')
                        bg-primary-100 dark:bg-primary-900/30
                        @break
                    @default
                        bg-surface-200 dark:bg-surface-700
                @endswitch">
                @switch($activity->action)
                    @case('create')
                        <i data-lucide="plus-circle" class="w-5 h-5 text-accent-emerald"></i>
                        @break
                    @case('update')
                        <i data-lucide="edit-3" class="w-5 h-5 text-accent-cyan"></i>
                        @break
                    @case('delete')
                        <i data-lucide="trash-2" class="w-5 h-5 text-accent-rose"></i>
                        @break
                    @case('login')
                        <i data-lucide="log-in" class="w-5 h-5 text-primary-600 dark:text-primary-400"></i>
                        @break
                    @default
                        <i data-lucide="activity" class="w-5 h-5 text-surface-500"></i>
                @endswitch
            </div>

            {{-- Activity Details --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-surface-900 dark:text-white truncate">
                    {{ $activity->description ?? ucfirst($activity->action) . ' ' . class_basename($activity->model_type) }}
                </p>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-surface-500 dark:text-surface-400">
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        {{ $activity->created_at->diffForHumans() }}
                    </span>
                    @if($activity->ip_address)
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="globe" class="w-3 h-3"></i>
                        {{ $activity->ip_address }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Action Badge --}}
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                    @switch($activity->action)
                        @case('create')
                            bg-accent-emerald/20 text-accent-emerald
                            @break
                        @case('update')
                            bg-accent-cyan/20 text-accent-cyan
                            @break
                        @case('delete')
                            bg-accent-rose/20 text-accent-rose
                            @break
                        @case('login')
                            bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400
                            @break
                        @default
                            bg-surface-200 dark:bg-surface-700 text-surface-600 dark:text-surface-400
                    @endswitch">
                    {{ ucfirst($activity->action) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
            <i data-lucide="inbox" class="w-8 h-8 text-surface-400"></i>
        </div>
        <p class="text-surface-500 dark:text-surface-400">Belum ada aktivitas tercatat</p>
    </div>
    @endif
</div>
