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
