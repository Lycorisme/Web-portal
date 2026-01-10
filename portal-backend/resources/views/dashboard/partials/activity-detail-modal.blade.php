{{-- Activity Detail Modal for Dashboard --}}
{{-- Use teleport to move modal outside of content wrapper for proper viewport centering --}}
<template x-teleport="body">
    <div 
        x-show="showActivityModal"
        x-cloak
        class="fixed inset-0 z-[9999] overflow-y-auto"
        aria-labelledby="activity-modal-title"
        role="dialog"
        aria-modal="true"
        style="margin: 0 !important; padding: 0 !important;"
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
            @click="closeActivityModal()"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm"
        ></div>

        {{-- Modal Container - Centered in viewport --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div 
                x-show="showActivityModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                @click.stop
                class="relative w-full max-w-3xl max-h-[85vh] flex flex-col bg-white dark:bg-surface-900 rounded-2xl shadow-2xl overflow-hidden"
            >
                <template x-if="selectedLog">
                    <div class="flex flex-col h-full max-h-[85vh]">
                        {{-- Modal Header with Action Badge --}}
                        <div class="relative flex-shrink-0">
                            {{-- Gradient Background based on action --}}
                            <div class="absolute inset-0 bg-theme-gradient opacity-10"></div>
                            
                            <div class="relative p-6 pb-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4">
                                        {{-- Action Icon --}}
                                        <div 
                                            class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg"
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
                                            <template x-if="selectedLog.action === 'CREATE'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                            </template>
                                            <template x-if="selectedLog.action === 'UPDATE'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </template>
                                            <template x-if="selectedLog.action === 'DELETE'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </template>
                                            <template x-if="selectedLog.action === 'LOGIN'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                                            </template>
                                            <template x-if="selectedLog.action === 'LOGOUT'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                            </template>
                                            <template x-if="selectedLog.action === 'LOGIN_FAILED'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                            </template>
                                            <template x-if="!['CREATE','UPDATE','DELETE','LOGIN','LOGOUT','LOGIN_FAILED'].includes(selectedLog.action)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                            </template>
                                        </div>
                                        
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <span 
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold"
                                                    :class="getActionBadgeClass(selectedLog.action)"
                                                    x-text="selectedLog.action_label || selectedLog.action"
                                                ></span>
                                                <span 
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize"
                                                    :class="getLevelBadgeClass(selectedLog.level)"
                                                    x-text="selectedLog.level || 'info'"
                                                ></span>
                                            </div>
                                            <h3 class="text-xl font-bold text-surface-900 dark:text-white" id="activity-modal-title">
                                                Detail Aktivitas
                                            </h3>
                                            <p class="text-sm text-surface-500 dark:text-surface-400 mt-1" x-text="selectedLog.created_at_human || selectedLog.created_at"></p>
                                        </div>
                                    </div>
                                    
                                    <button 
                                        @click="closeActivityModal()"
                                        class="p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-500"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
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
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-violet-600 dark:text-violet-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">User</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-theme-gradient flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold" x-text="(selectedLog.user_name || 'S').charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-surface-900 dark:text-white text-sm truncate" x-text="selectedLog.user_name || 'System'"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- IP Address Card --}}
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 dark:text-blue-400"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">IP Address</span>
                                    </div>
                                    <code class="text-sm font-mono font-semibold text-surface-900 dark:text-white bg-surface-100 dark:bg-surface-700 px-2 py-1 rounded-lg block truncate" x-text="selectedLog.ip_address || '-'"></code>
                                </div>

                                {{-- Subject Card --}}
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 dark:text-amber-400"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
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
                                <div class="bg-white dark:bg-surface-800/50 rounded-xl p-4 border border-surface-200/50 dark:border-surface-700/50 shadow-sm">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 dark:text-emerald-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wide">Waktu</span>
                                    </div>
                                    <p class="font-semibold text-surface-900 dark:text-white text-sm" x-text="selectedLog.created_at"></p>
                                </div>
                            </div>

                            {{-- Data Changes Section --}}
                            <template x-if="selectedLog.old_values || selectedLog.new_values">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-surface-500"><circle cx="18" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><path d="M6 21V9a9 9 0 0 0 9 9"/></svg>
                                        <h4 class="font-semibold text-surface-900 dark:text-white">Perubahan Data</h4>
                                    </div>

                                    <div class="overflow-hidden rounded-xl border border-surface-200/50 dark:border-surface-700/50 bg-white dark:bg-surface-800">
                                        <table class="w-full text-left text-sm">
                                            <thead class="bg-surface-50 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700 text-surface-500 font-semibold">
                                                <tr>
                                                    <th class="p-3 w-1/3">Data</th>
                                                    <th class="p-3 text-rose-600 dark:text-rose-400 w-1/3">Sebelum</th>
                                                    <th class="p-3 text-emerald-600 dark:text-emerald-400 w-1/3">Sesudah</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                                                <template x-for="key in getAllKeys(selectedLog.old_values, selectedLog.new_values)" :key="key">
                                                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                                                        <td class="p-3">
                                                            <span class="font-medium text-surface-700 dark:text-surface-300 capitalize" x-text="formatKey(key)"></span>
                                                        </td>
                                                        <td class="p-3 text-surface-600 dark:text-surface-400 align-top">
                                                            <div class="p-2 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/10">
                                                                <span class="font-mono text-xs break-all" x-text="formatValue(selectedLog.old_values ? selectedLog.old_values[key] : null)"></span>
                                                            </div>
                                                        </td>
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

                        {{-- Modal Footer --}}
                        <div class="p-4 border-t border-surface-200/50 dark:border-surface-800/50 bg-surface-50/50 dark:bg-surface-800/30 flex-shrink-0 flex items-center justify-between">
                            <span class="text-xs text-surface-400 dark:text-surface-500 font-mono" x-text="'Log ID: #' + selectedLog.id"></span>
                            <button 
                                @click="closeActivityModal()"
                                class="px-4 py-2 text-sm font-semibold text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700 rounded-xl transition-all shadow-sm"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Loading State --}}
                <div x-show="showActivityModal && !selectedLog" class="flex items-center justify-center py-20">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-theme-500 border-t-transparent"></div>
                </div>
            </div>
        </div>
    </div>
</template>
