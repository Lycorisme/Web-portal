{{-- Activity Modal --}}
<template x-teleport="body">
    <div 
        x-show="showActivityModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="activity-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showActivityModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-md"
            @click="closeActivityModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
            <div 
                x-show="showActivityModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-xl bg-white dark:bg-surface-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden max-h-[85vh] flex flex-col"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex-shrink-0 px-6 py-5 border-b border-surface-100 dark:border-surface-800 flex items-center justify-between bg-white/80 dark:bg-surface-900/80 backdrop-blur-md sticky top-0 z-10">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/20">
                            <i data-lucide="history" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white truncate leading-tight">Log Aktivitas</h3>
                            <p type="button" class="text-xs font-medium text-surface-500 mt-0.5 truncate" x-text="activityLogArticleTitle || 'Detail perubahan artikel'"></p>
                        </div>
                    </div>
                    <button 
                        type="button"
                        @click="closeActivityModal()" 
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-100 hover:bg-surface-200 dark:bg-surface-800 dark:hover:bg-surface-700 text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 transition-colors"
                    >
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
                    {{-- Loading State --}}
                    <div x-show="activityLogLoading" class="flex flex-col items-center justify-center py-20">
                        <div class="w-10 h-10 border-2 border-theme-500/30 border-t-theme-500 rounded-full animate-spin mb-4"></div>
                        <span class="text-sm font-medium text-surface-500">Memuat riwayat aktivitas...</span>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="!activityLogLoading && (!activityLogs || activityLogs.length === 0)" class="flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="w-16 h-16 bg-surface-50 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="clock" class="w-8 h-8 text-surface-300 dark:text-surface-600"></i>
                        </div>
                        <p class="text-surface-900 dark:text-white font-medium">Belum ada aktivitas</p>
                        <p class="text-sm text-surface-500 mt-1">Belum ada riwayat perubahan tercatat untuk artikel ini.</p>
                    </div>

                    {{-- Timeline --}}
                    <div x-show="!activityLogLoading && activityLogs?.length > 0" class="relative px-6 py-8">
                        {{-- Timeline Line --}}
                        <div class="absolute left-10 top-8 bottom-8 w-px bg-surface-200 dark:bg-surface-700/50"></div>

                        <div class="space-y-8 relative">
                            <template x-for="(log, index) in activityLogs" :key="log.id">
                                <div class="relative pl-10 group">
                                    {{-- Dot --}}
                                    <div class="absolute left-[-5px] top-1.5 w-2.5 h-2.5 rounded-full ring-4 ring-white dark:ring-surface-900 z-10"
                                         :class="{
                                             'bg-emerald-500': log.event === 'created' || log.event === 'restore',
                                             'bg-amber-500': log.event === 'updated',
                                             'bg-rose-500': log.event === 'deleted' || log.event === 'force_deleted',
                                             'bg-blue-500': !['created', 'updated', 'deleted', 'force_deleted', 'restore'].includes(log.event)
                                         }">
                                    </div>

                                    {{-- Card --}}
                                    <div class="bg-surface-50 dark:bg-surface-800/40 rounded-xl p-4 border border-surface-100 dark:border-surface-700/50 hover:bg-white dark:hover:bg-surface-800 hover:shadow-sm transition-all duration-300">
                                        {{-- Header --}}
                                        <div class="flex items-start justify-between gap-4 mb-2">
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="text-sm font-bold text-surface-900 dark:text-white" x-text="log.description"></span>
                                                    <span 
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                                        :class="{
                                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400': log.event === 'created' || log.event === 'restore',
                                                            'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400': log.event === 'updated',
                                                            'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400': log.event === 'deleted' || log.event === 'force_deleted',
                                                            'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400': !['created', 'updated', 'deleted', 'force_deleted', 'restore'].includes(log.event)
                                                        }"
                                                        x-text="log.event"
                                                    ></span>
                                                </div>
                                                <p class="text-xs text-surface-500 mt-1" x-text="log.created_at_human"></p>
                                            </div>
                                        </div>

                                        {{-- User --}}
                                        <div class="flex items-center gap-2 mb-3 px-2 py-1.5 rounded-lg bg-surface-100/50 dark:bg-surface-700/30 w-fit">
                                            <div class="w-5 h-5 rounded-full overflow-hidden bg-surface-200 dark:bg-surface-600 flex items-center justify-center">
                                                <img 
                                                    x-show="log.causer_avatar" 
                                                    :src="log.causer_avatar" 
                                                    class="w-full h-full object-cover"
                                                >
                                                <span x-show="!log.causer_avatar" x-text="(log.causer_name || '?').charAt(0)" class="text-[10px] font-bold text-surface-500 dark:text-surface-300"></span>
                                            </div>
                                            <span class="text-xs font-medium text-surface-700 dark:text-surface-300" x-text="log.causer_name || 'System'"></span>
                                        </div>

                                        {{-- Changes (Old vs New) --}}
                                        <template x-if="log.properties && (log.properties.attributes || log.properties.old)">
                                            <div class="space-y-2 mt-3 text-xs bg-white dark:bg-surface-900 rounded-lg p-3 border border-surface-100 dark:border-surface-700/50">
                                                <template x-if="log.properties.old">
                                                    <div>
                                                        <span class="block text-rose-500 font-bold mb-1">Sebelumnya:</span>
                                                        <div class="space-y-1">
                                                            <template x-for="(value, key) in log.properties.old" :key="'old-'+key">
                                                                <div x-show="key !== 'updated_at'" class="grid grid-cols-3 gap-2">
                                                                    <span class="text-surface-500 truncate" x-text="key"></span>
                                                                    <span class="col-span-2 text-surface-700 dark:text-surface-300 break-words" x-text="value"></span>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="log.properties.old && log.properties.attributes">
                                                    <div class="border-t border-surface-100 dark:border-surface-800 my-2"></div>
                                                </template>

                                                <template x-if="log.properties.attributes">
                                                    <div>
                                                        <span class="block text-emerald-500 font-bold mb-1" x-text="log.event === 'created' ? 'Data:' : 'Menjadi:'"></span>
                                                        <div class="space-y-1">
                                                            <template x-for="(value, key) in log.properties.attributes" :key="'new-'+key">
                                                                <div x-show="key !== 'updated_at' && key !== 'created_at'" class="grid grid-cols-3 gap-2">
                                                                    <span class="text-surface-500 truncate" x-text="key"></span>
                                                                    <span class="col-span-2 text-surface-700 dark:text-surface-300 break-words font-medium" x-text="value"></span>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex-shrink-0 px-6 py-4 bg-surface-50/50 dark:bg-surface-900/50 backdrop-blur border-t border-surface-100 dark:border-surface-800">
                    <button 
                        type="button"
                        @click="closeActivityModal()"
                        class="w-full px-4 py-2.5 text-sm font-semibold text-surface-600 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 border border-surface-200 dark:border-surface-700 rounded-xl transition-colors shadow-sm"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
