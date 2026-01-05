{{-- Detail Modal - Redesigned for better UX --}}
<template x-teleport="body">
    <div 
        x-show="showDetailModal"
        x-cloak
        class="fixed inset-0 z-[100] overflow-y-auto"
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
            @click="closeDetailModal()"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        ></div>

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div 
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                @click.away="closeDetailModal()"
                class="relative w-full max-w-3xl h-[85vh] flex flex-col bg-white dark:bg-surface-900 rounded-2xl shadow-2xl overflow-hidden"
            >
                <template x-if="selectedLog">
                    <div class="flex flex-col h-full max-h-full">
                        {{-- Modal Header with Action Badge --}}
                        <div class="relative flex-shrink-0">
                            {{-- Gradient Background based on action --}}
                            <div class="absolute inset-0 bg-theme-gradient opacity-10"></div>
                            
                            <div class="relative p-6 pb-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4">
                                        {{-- Action Icon --}}
                                        <div 
                                            class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
                                            :class="{
                                                'bg-gradient-to-br from-emerald-400 to-emerald-600': selectedLog.action === 'CREATE',
                                                'bg-gradient-to-br from-blue-400 to-blue-600': selectedLog.action === 'UPDATE',
                                                'bg-gradient-to-br from-rose-400 to-rose-600': selectedLog.action === 'DELETE',
                                                'bg-gradient-to-br from-violet-400 to-violet-600': selectedLog.action === 'LOGIN',
                                                'bg-gradient-to-br from-slate-400 to-slate-600': selectedLog.action === 'LOGOUT',
                                                'bg-gradient-to-br from-orange-400 to-orange-600': selectedLog.action === 'LOGIN_FAILED',
                                                'bg-gradient-to-br from-surface-400 to-surface-600': !['CREATE','UPDATE','DELETE','LOGIN','LOGOUT','LOGIN_FAILED'].includes(selectedLog.action)
                                            }"
                                        >
                                            <i 
                                                :data-lucide="selectedLog.action === 'CREATE' ? 'plus' : 
                                                              selectedLog.action === 'UPDATE' ? 'pencil' : 
                                                              selectedLog.action === 'DELETE' ? 'trash-2' : 
                                                              selectedLog.action === 'LOGIN' ? 'log-in' : 
                                                              selectedLog.action === 'LOGOUT' ? 'log-out' : 
                                                              selectedLog.action === 'LOGIN_FAILED' ? 'shield-alert' : 'activity'"
                                                class="w-5 h-5 sm:w-7 sm:h-7 text-white"
                                            ></i>
                                        </div>
                                        
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <span 
                                                    class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-bold"
                                                    :class="getActionBadgeClass(selectedLog.action)"
                                                    x-text="selectedLog.action_label"
                                                ></span>
                                                <span 
                                                    class="inline-flex items-center px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded text-[10px] sm:text-xs font-medium capitalize"
                                                    :class="getLevelBadgeClass(selectedLog.level)"
                                                    x-text="selectedLog.level"
                                                ></span>
                                            </div>
                                            <h3 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white" id="modal-title">
                                                Detail Aktivitas
                                            </h3>
                                            <p class="text-sm text-surface-500 dark:text-surface-400 mt-1" x-text="selectedLog.created_at_human"></p>
                                        </div>
                                    </div>
                                    
                                    <button 
                                        @click="closeDetailModal()"
                                        class="p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-colors"
                                    >
                                        <i data-lucide="x" class="w-5 h-5 text-surface-500"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Body (Scrollable) --}}
                        <div class="p-6 pt-2 overflow-y-auto flex-grow custom-scrollbar">
                            {{-- Summary Card --}}
                            <div class="bg-gradient-to-br from-surface-50 to-surface-100 dark:from-surface-800/50 dark:to-surface-800 rounded-2xl p-5 mb-6 border border-surface-200/50 dark:border-surface-700/50">
                                <p class="text-surface-800 dark:text-surface-200 text-base leading-relaxed" x-text="selectedLog.description"></p>
                            </div>

                            {{-- Info Cards Grid --}}
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                {{-- User Card --}}
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                                            <i data-lucide="user" class="w-4 h-4 text-violet-600 dark:text-violet-400"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">User</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-theme-gradient flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold" x-text="selectedLog.user_name?.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-surface-900 dark:text-white text-sm truncate" x-text="selectedLog.user_name"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- IP Address Card --}}
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                            <i data-lucide="globe" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">IP Address</span>
                                    </div>
                                    <code class="text-sm font-mono font-semibold text-surface-900 dark:text-white bg-surface-100 dark:bg-surface-700 px-2 py-1 rounded-lg block truncate" x-text="selectedLog.ip_address || '-'"></code>
                                </div>

                                {{-- Subject Card --}}
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                                            <i data-lucide="file-text" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">Subject</span>
                                    </div>
                                    <template x-if="selectedLog.subject_type">
                                        <div>
                                            <p class="font-semibold text-surface-900 dark:text-white text-sm" x-text="selectedLog.subject_type"></p>
                                            <p class="text-xs text-surface-500 font-mono" x-text="'ID: ' + selectedLog.subject_id"></p>
                                        </div>
                                    </template>
                                    <template x-if="!selectedLog.subject_type">
                                        <p class="text-sm text-surface-400">Tidak ada</p>
                                    </template>
                                </div>

                                {{-- Timestamp Card --}}
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                                            <i data-lucide="clock" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">Waktu</span>
                                    </div>
                                    <p class="font-semibold text-surface-900 dark:text-white text-sm" x-text="selectedLog.created_at"></p>
                                </div>
                            </div>

                            {{-- Collapsible Technical Details --}}
                            <div x-data="{ showTechnical: false }" class="mb-6">
                                <button 
                                    @click="showTechnical = !showTechnical"
                                    class="w-full flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-200/50 dark:border-surface-700/50 hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-surface-200 dark:bg-surface-700 flex items-center justify-center">
                                            <i data-lucide="code-2" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
                                        </div>
                                        <span class="font-semibold text-surface-900 dark:text-white">Detail Teknis</span>
                                    </div>
                                    <i 
                                        data-lucide="chevron-down" 
                                        class="w-5 h-5 text-surface-500 transition-transform duration-200"
                                        :class="showTechnical ? 'rotate-180' : ''"
                                    ></i>
                                </button>
                                
                                <div 
                                    x-show="showTechnical"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="mt-3 space-y-3"
                                >
                                    {{-- URL --}}
                                    <div class="bg-surface-50 dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i data-lucide="link" class="w-4 h-4 text-surface-500"></i>
                                            <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">URL</span>
                                        </div>
                                        <p class="text-sm font-mono text-surface-700 dark:text-surface-300 break-all bg-surface-100 dark:bg-surface-700 p-2 rounded-lg" x-text="selectedLog.url || '-'"></p>
                                    </div>

                                    {{-- User Agent --}}
                                    <div class="bg-surface-50 dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i data-lucide="monitor" class="w-4 h-4 text-surface-500"></i>
                                            <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">Browser / Device</span>
                                        </div>
                                        <p class="text-xs text-surface-600 dark:text-surface-400 break-all bg-surface-100 dark:bg-surface-700 p-2 rounded-lg leading-relaxed" x-text="selectedLog.user_agent || '-'"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Data Changes Section --}}
                            <template x-if="selectedLog.old_values || selectedLog.new_values">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i data-lucide="git-compare" class="w-5 h-5 text-surface-500"></i>
                                        <h4 class="font-semibold text-surface-900 dark:text-white">Perubahan Data</h4>
                                    </div>

                                    <div class="overflow-hidden rounded-xl border border-surface-200/50 dark:border-surface-700/50 bg-white dark:bg-surface-800">
                                        <table class="w-full text-left text-sm">
                                            <thead class="bg-surface-50 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700 text-surface-500 font-semibold">
                                                <tr>
                                                    <th class="p-3 w-1/3">Data</th>
                                                    <th class="p-3 text-rose-600 dark:text-rose-400 w-1/3">
                                                        <div class="flex items-center gap-2">
                                                            <i data-lucide="minus-circle" class="w-4 h-4"></i>
                                                            Sebelum
                                                        </div>
                                                    </th>
                                                    <th class="p-3 text-emerald-600 dark:text-emerald-400 w-1/3">
                                                        <div class="flex items-center gap-2">
                                                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                                            Sesudah
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                                                <template x-for="key in getAllKeys(selectedLog.old_values, selectedLog.new_values)" :key="key">
                                                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                                                        <td class="p-3">
                                                            <span class="font-medium text-surface-700 dark:text-surface-300 capitalize" x-text="formatKey(key)"></span>
                                                            <div class="text-[10px] text-surface-400 font-mono mt-0.5" x-text="key"></div>
                                                        </td>
                                                        
                                                        {{-- Old Value --}}
                                                        <td class="p-3 text-surface-600 dark:text-surface-400 align-top">
                                                            <div class="p-2 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/10">
                                                                <span class="font-mono text-xs break-all" x-text="formatValue(selectedLog.old_values ? selectedLog.old_values[key] : null)"></span>
                                                            </div>
                                                        </td>

                                                        {{-- New Value --}}
                                                        <td class="p-3 text-surface-600 dark:text-surface-400 align-top">
                                                            <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/10">
                                                                <span class="font-mono text-xs break-all" x-text="formatValue(selectedLog.new_values ? selectedLog.new_values[key] : null)"></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Modal Footer with ID only --}}
                        <div class="p-4 border-t border-surface-200/50 dark:border-surface-800/50 bg-surface-50/50 dark:bg-surface-800/30 flex-shrink-0">
                            <span class="text-xs text-surface-400 dark:text-surface-500 font-mono" x-text="'Log ID: #' + selectedLog.id"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
