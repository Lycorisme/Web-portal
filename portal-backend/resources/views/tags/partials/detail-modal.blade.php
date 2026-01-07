{{-- Detail Modal --}}
<template x-teleport="body">
    <div 
        x-show="showDetailModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showDetailModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="closeDetailModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.stop
            >
                {{-- Header with Theme Color --}}
                <template x-if="selectedTag">
                    <div>
                        <div class="px-6 py-5 bg-gradient-to-r from-theme-500 to-theme-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <i data-lucide="hash" class="w-6 h-6 text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white" x-text="selectedTag.name"></h3>
                                        <code class="text-sm text-white/80" x-text="'/tag/' + selectedTag.slug"></code>
                                    </div>
                                </div>
                                <button @click="closeDetailModal()" class="p-2 rounded-xl hover:bg-white/20 transition-colors">
                                    <i data-lucide="x" class="w-5 h-5 text-white"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 space-y-5">
                            {{-- Article Count Badge --}}
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    <span x-text="selectedTag.articles_count + ' Artikel'"></span>
                                </span>
                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                    :class="selectedTag.is_active 
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' 
                                        : 'bg-surface-100 text-surface-500 dark:bg-surface-700 dark:text-surface-400'"
                                >
                                    <i :data-lucide="selectedTag.is_active ? 'check-circle' : 'x-circle'" class="w-4 h-4"></i>
                                    <span x-text="selectedTag.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                </span>
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Slug --}}
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl col-span-2">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Slug URL</label>
                                    <code class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedTag.slug"></code>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Dibuat</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedTag.created_at"></p>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Terakhir Update</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedTag.updated_at"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center gap-3">
                            <button 
                                @click="closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"
                            >
                                Tutup
                            </button>
                            <button 
                                @click="openEditModal(selectedTag); closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 flex items-center justify-center gap-2"
                            >
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                <span>Edit</span>
                            </button>
                        </div>


                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
