{{-- Notification Modal --}}
<template x-teleport="body">
    <div 
        x-show="showNotificationModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showNotificationModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="showNotificationModal = false"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showNotificationModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Notifikasi</h3>
                        <button @click="showNotificationModal = false" class="p-2 rounded-xl hover:bg-white/20 transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>

                {{-- Content --}}
                <div class="max-h-[60vh] overflow-y-auto">
                    {{-- Notification List --}}
                    <div class="divide-y divide-surface-100 dark:divide-surface-800/50">
                        <a href="#"
                            class="flex items-start gap-4 p-5 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-accent-emerald/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="check-circle" class="w-5 h-5 text-accent-emerald"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-surface-900 dark:text-white group-hover:text-theme-600 transition-colors">Berita berhasil dipublish</p>
                                <p class="text-xs text-surface-500 mt-1">2 menit yang lalu</p>
                            </div>
                        </a>
                        <a href="#"
                            class="flex items-start gap-4 p-5 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-accent-amber/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="shield-alert" class="w-5 h-5 text-accent-amber"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-surface-900 dark:text-white group-hover:text-theme-600 transition-colors">IP 192.168.1.45 terblokir</p>
                                <p class="text-xs text-surface-500 mt-1">15 menit yang lalu</p>
                            </div>
                        </a>
                        <a href="#"
                            class="flex items-start gap-4 p-5 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-theme-100 dark:bg-theme-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="user-plus" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-surface-900 dark:text-white group-hover:text-theme-600 transition-colors">User baru terdaftar</p>
                                <p class="text-xs text-surface-500 mt-1">1 jam yang lalu</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-4 border-t border-surface-200 dark:border-surface-800 bg-surface-50 dark:bg-surface-800/50">
                    <a href="#" class="block w-full py-2.5 text-center text-sm font-medium text-theme-600 dark:text-theme-400 hover:text-theme-700 dark:hover:text-theme-300 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
