{{-- Detail Modal --}}
<template x-teleport="body">
    <div 
        x-show="showDetailModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        @keydown.escape.window="closeDetailModal()"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showDetailModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            @click="closeDetailModal()"
        ></div>

        {{-- Modal Content --}}
        <div 
            x-show="showDetailModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-2xl bg-white dark:bg-surface-900 rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
        >
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-800 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl"
                         :class="selectedClient?.is_blocked ? 'bg-rose-50 dark:bg-rose-500/10' : 'bg-emerald-50 dark:bg-emerald-500/10'">
                        <i :data-lucide="selectedClient?.is_blocked ? 'shield-ban' : 'shield-check'" 
                           class="w-5 h-5"
                           :class="selectedClient?.is_blocked ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-white">Detail IP Blokir</h3>
                        <p class="text-sm font-mono text-surface-500 dark:text-surface-400" x-text="selectedClient?.ip_address"></p>
                    </div>
                </div>
                <button 
                    @click="closeDetailModal()"
                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-lg transition-colors"
                >
                    <i data-lucide="x" class="w-5 h-5 text-surface-500"></i>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 overflow-y-auto flex-1">
                {{-- Status Banner --}}
                <div class="p-4 rounded-xl mb-6"
                     :class="{
                         'bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20': selectedClient?.is_blocked && !selectedClient?.is_expired,
                         'bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20': selectedClient?.is_blocked && selectedClient?.is_expired,
                         'bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20': !selectedClient?.is_blocked
                     }">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg"
                             :class="{
                                 'bg-rose-100 dark:bg-rose-500/20': selectedClient?.is_blocked && !selectedClient?.is_expired,
                                 'bg-amber-100 dark:bg-amber-500/20': selectedClient?.is_blocked && selectedClient?.is_expired,
                                 'bg-emerald-100 dark:bg-emerald-500/20': !selectedClient?.is_blocked
                             }">
                            <i :data-lucide="selectedClient?.is_blocked ? (selectedClient?.is_expired ? 'clock' : 'shield-ban') : 'shield-check'" 
                               class="w-5 h-5"
                               :class="{
                                   'text-rose-600 dark:text-rose-400': selectedClient?.is_blocked && !selectedClient?.is_expired,
                                   'text-amber-600 dark:text-amber-400': selectedClient?.is_blocked && selectedClient?.is_expired,
                                   'text-emerald-600 dark:text-emerald-400': !selectedClient?.is_blocked
                               }"></i>
                        </div>
                        <div>
                            <p class="font-semibold"
                               :class="{
                                   'text-rose-700 dark:text-rose-400': selectedClient?.is_blocked && !selectedClient?.is_expired,
                                   'text-amber-700 dark:text-amber-400': selectedClient?.is_blocked && selectedClient?.is_expired,
                                   'text-emerald-700 dark:text-emerald-400': !selectedClient?.is_blocked
                               }"
                               x-text="selectedClient?.is_blocked 
                                   ? (selectedClient?.is_expired ? 'Blokir Expired' : 'Sedang Terblokir') 
                                   : 'Tidak Terblokir'">
                            </p>
                            <p class="text-sm"
                               :class="{
                                   'text-rose-600 dark:text-rose-300': selectedClient?.is_blocked && !selectedClient?.is_expired,
                                   'text-amber-600 dark:text-amber-300': selectedClient?.is_blocked && selectedClient?.is_expired,
                                   'text-emerald-600 dark:text-emerald-300': !selectedClient?.is_blocked
                               }"
                               x-text="selectedClient?.blocked_until 
                                   ? (selectedClient?.is_expired ? 'Blokir telah kadaluarsa' : 'Expires: ' + selectedClient?.blocked_until)
                                   : (selectedClient?.is_blocked ? 'Blokir permanen' : 'IP dapat mengakses sistem')">
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- IP Address --}}
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-2 text-xs text-surface-500 mb-1">
                            <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                            <span>IP Address</span>
                        </div>
                        <p class="font-mono font-semibold text-surface-900 dark:text-white" x-text="selectedClient?.ip_address"></p>
                    </div>

                    {{-- Attempt Count --}}
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-2 text-xs text-surface-500 mb-1">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                            <span>Jumlah Percobaan</span>
                        </div>
                        <p class="font-semibold text-surface-900 dark:text-white" x-text="selectedClient?.attempt_count + ' kali'"></p>
                    </div>

                    {{-- Blocked Route --}}
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-2 text-xs text-surface-500 mb-1">
                            <i data-lucide="route" class="w-3.5 h-3.5"></i>
                            <span>Route Terblokir</span>
                        </div>
                        <p class="font-mono text-sm text-surface-900 dark:text-white" x-text="selectedClient?.blocked_route || '-'"></p>
                    </div>

                    {{-- Duration --}}
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-2 text-xs text-surface-500 mb-1">
                            <i data-lucide="timer" class="w-3.5 h-3.5"></i>
                            <span>Durasi Blokir</span>
                        </div>
                        <p class="font-semibold text-surface-900 dark:text-white" x-text="selectedClient?.blocked_until ? 'Sementara' : 'Permanen'"></p>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="mt-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                    <div class="flex items-center gap-2 text-xs text-surface-500 mb-2">
                        <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                        <span>Alasan Blokir</span>
                    </div>
                    <p class="text-sm text-surface-700 dark:text-surface-300" x-text="selectedClient?.reason || 'Tidak ada alasan yang tercatat'"></p>
                </div>

                {{-- User Agent --}}
                <div class="mt-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                    <div class="flex items-center gap-2 text-xs text-surface-500 mb-2">
                        <i data-lucide="monitor-smartphone" class="w-3.5 h-3.5"></i>
                        <span>User Agent</span>
                    </div>
                    <p class="text-xs font-mono text-surface-600 dark:text-surface-400 break-all" x-text="selectedClient?.user_agent || 'Tidak ada data'"></p>
                </div>

                {{-- Timestamps --}}
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-2 text-xs text-surface-500 mb-1">
                            <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                            <span>Dibuat</span>
                        </div>
                        <p class="text-sm text-surface-700 dark:text-surface-300" x-text="selectedClient?.created_at"></p>
                    </div>
                    <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                        <div class="flex items-center gap-2 text-xs text-surface-500 mb-1">
                            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
                            <span>Terakhir Diperbarui</span>
                        </div>
                        <p class="text-sm text-surface-700 dark:text-surface-300" x-text="selectedClient?.updated_at"></p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-800 flex justify-end gap-3 flex-shrink-0">
                <button 
                    @click="closeDetailModal()"
                    class="px-5 py-2.5 text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-colors"
                >
                    Tutup
                </button>
                <button 
                    x-show="selectedClient?.is_blocked"
                    @click="unblockClient(selectedClient?.id); closeDetailModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white font-medium text-sm rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20"
                >
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>Unblock</span>
                </button>
            </div>
        </div>
    </div>
</template>
