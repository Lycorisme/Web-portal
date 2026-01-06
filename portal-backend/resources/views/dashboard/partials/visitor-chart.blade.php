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
