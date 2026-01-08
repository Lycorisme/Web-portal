{{-- Pagination Section --}}
<div class="p-4 sm:p-6 border-t border-surface-200/50 dark:border-surface-800/50">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Info --}}
        <div class="text-sm text-surface-500 dark:text-surface-400">
            <template x-if="meta.total > 0">
                <span>
                    Menampilkan <span class="font-medium text-surface-900 dark:text-white" x-text="meta.from"></span>
                    - <span class="font-medium text-surface-900 dark:text-white" x-text="meta.to"></span>
                    dari <span class="font-medium text-surface-900 dark:text-white" x-text="meta.total"></span> item
                </span>
            </template>
            <template x-if="meta.total === 0">
                <span>Tidak ada data</span>
            </template>
        </div>

        {{-- Pagination Controls --}}
        <div class="flex items-center gap-1">
            {{-- First Page --}}
            <button 
                @click="goToPage(1)"
                :disabled="meta.current_page === 1"
                :class="meta.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-100 dark:hover:bg-surface-800'"
                class="p-2 rounded-lg transition-colors"
            >
                <i data-lucide="chevrons-left" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
            </button>

            {{-- Previous Page --}}
            <button 
                @click="goToPage(meta.current_page - 1)"
                :disabled="meta.current_page === 1"
                :class="meta.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-100 dark:hover:bg-surface-800'"
                class="p-2 rounded-lg transition-colors"
            >
                <i data-lucide="chevron-left" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
            </button>

            {{-- Page Numbers --}}
            <template x-for="page in paginationPages" :key="'page-' + page">
                <template x-if="page === '...'">
                    <span class="px-2 py-1 text-surface-400">...</span>
                </template>
                <template x-if="page !== '...'">
                    <button 
                        @click="goToPage(page)"
                        :class="meta.current_page === page 
                            ? 'bg-theme-gradient text-white shadow-md' 
                            : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                        class="min-w-[36px] h-9 px-3 rounded-lg font-medium text-sm transition-all"
                        x-text="page"
                    ></button>
                </template>
            </template>

            {{-- Next Page --}}
            <button 
                @click="goToPage(meta.current_page + 1)"
                :disabled="meta.current_page === meta.last_page"
                :class="meta.current_page === meta.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-100 dark:hover:bg-surface-800'"
                class="p-2 rounded-lg transition-colors"
            >
                <i data-lucide="chevron-right" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
            </button>

            {{-- Last Page --}}
            <button 
                @click="goToPage(meta.last_page)"
                :disabled="meta.current_page === meta.last_page"
                :class="meta.current_page === meta.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-100 dark:hover:bg-surface-800'"
                class="p-2 rounded-lg transition-colors"
            >
                <i data-lucide="chevrons-right" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
            </button>
        </div>
    </div>
</div>
