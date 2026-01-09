<template x-teleport="body">
    <div 
        x-show="selectedIds.length > 0"
        x-transition:enter="transition ease-custom-spring duration-500"
        x-transition:enter-start="translate-y-24 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-24 opacity-0 scale-95"
        class="fixed z-[100] bottom-4 sm:bottom-10 left-0 right-0 flex justify-center px-4 pointer-events-none"
        x-cloak
    >
        <div class="pointer-events-auto bg-white/95 dark:bg-surface-800/95 backdrop-blur-xl border border-theme-100 dark:border-surface-600 p-3 sm:p-2 sm:pr-3 sm:pl-4 rounded-xl sm:rounded-2xl shadow-2xl shadow-theme-500/10 dark:shadow-black/50 flex flex-col sm:flex-row items-center gap-3 sm:gap-8 w-full sm:w-auto max-w-md sm:max-w-2xl ring-1 ring-theme-500/10 dark:ring-surface-500/30 transform transition-all">
            
            {{-- Selected Count --}}
            <div class="flex items-center justify-between w-full sm:w-auto gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-theme-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-theme-500"></span>
                    </span>
                    <span class="text-theme-600 dark:text-theme-400 font-semibold text-sm whitespace-nowrap">
                        <span x-text="selectedIds.length"></span> IP dipilih
                    </span>
                </div>
                
                {{-- Mobile Close Button --}}
                <button 
                    @click="selectedIds = []; selectAll = false;"
                    class="sm:hidden p-1 text-surface-400 hover:text-surface-600 dark:hover:text-surface-200"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Separator (Desktop) --}}
            <div class="hidden sm:block h-8 w-px bg-surface-200 dark:bg-surface-700"></div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 w-full sm:w-auto">
                {{-- Cancel (Desktop) --}}
                <button 
                    @click="selectedIds = []; selectAll = false;"
                    class="hidden sm:block px-3 py-2 text-sm font-medium text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 transition-colors"
                >
                    Batal
                </button>

                {{-- Bulk Actions --}}
                <div class="grid grid-cols-2 gap-2 w-full sm:flex sm:w-auto">
                    {{-- Bulk Unblock --}}
                    <button 
                        @click="bulkUnblock()"
                        class="group flex items-center justify-center gap-2 px-3 py-2.5 bg-emerald-500 text-white rounded-lg sm:rounded-xl text-sm font-semibold shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 hover:bg-emerald-600 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                    >
                        <i data-lucide="shield-check" class="w-4 h-4 transition-transform group-hover:scale-110"></i>
                        <span class="whitespace-nowrap">Unblock</span>
                    </button>

                    {{-- Bulk Delete --}}
                    <button 
                        @click="bulkDelete()"
                        class="group flex items-center justify-center gap-2 px-3 py-2.5 bg-rose-500 text-white rounded-lg sm:rounded-xl text-sm font-semibold shadow-lg shadow-rose-500/20 hover:shadow-rose-500/40 hover:bg-rose-600 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                    >
                        <i data-lucide="trash-2" class="w-4 h-4 transition-transform group-hover:scale-110"></i>
                        <span class="whitespace-nowrap">Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    /* Custom spring animation for a "bouncy" feel */
    .ease-custom-spring {
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>
