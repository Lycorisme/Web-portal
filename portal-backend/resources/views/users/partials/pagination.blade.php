{{-- Pagination Section --}}
<div class="px-4 sm:px-6 py-4 border-t border-surface-200/50 dark:border-surface-800/50">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Results Info --}}
        <div class="text-sm text-surface-600 dark:text-surface-400 order-2 sm:order-1">
            <template x-if="meta.total > 0">
                <span>
                    Menampilkan <span class="font-semibold text-surface-900 dark:text-white" x-text="meta.from"></span>
                    - <span class="font-semibold text-surface-900 dark:text-white" x-text="meta.to"></span>
                    dari <span class="font-semibold text-surface-900 dark:text-white" x-text="meta.total"></span> user
                </span>
            </template>
            <template x-if="meta.total === 0">
                <span>Tidak ada data</span>
            </template>
        </div>

        {{-- Pagination Controls --}}
        <div class="flex items-center gap-2 order-1 sm:order-2">
            {{-- Previous Button --}}
            <button 
                @click="goToPage(meta.current_page - 1)"
                :disabled="meta.current_page <= 1"
                class="p-2 rounded-lg border border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            {{-- Page Numbers --}}
            <div class="flex items-center gap-1">
                <template x-for="page in getVisiblePages()" :key="page">
                    <button 
                        @click="page !== '...' && goToPage(page)"
                        :disabled="page === '...'"
                        class="min-w-[2.25rem] h-9 px-2 rounded-lg text-sm font-medium transition-all"
                        :class="page === meta.current_page 
                            ? 'bg-theme-gradient text-white shadow-lg shadow-theme-500/20' 
                            : page === '...'
                                ? 'cursor-default text-surface-400'
                                : 'border border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                        x-text="page"
                    ></button>
                </template>
            </div>

            {{-- Next Button --}}
            <button 
                @click="goToPage(meta.current_page + 1)"
                :disabled="meta.current_page >= meta.last_page"
                class="p-2 rounded-lg border border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>
