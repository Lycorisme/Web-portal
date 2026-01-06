{{-- Auto Delete Settings Modal --}}
<template x-teleport="body">
    <div 
        x-show="showAutoDeleteModal"
        x-cloak
        class="fixed inset-0 z-[100] overflow-y-auto"
        aria-labelledby="auto-delete-modal-title"
        role="dialog"
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showAutoDeleteModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="closeAutoDeleteModal()"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        ></div>

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div 
                x-show="showAutoDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                @click.away="closeAutoDeleteModal()"
                class="relative w-full max-w-lg bg-white dark:bg-surface-900 rounded-2xl shadow-2xl overflow-hidden"
            >
                <form @submit.prevent="saveAutoDeleteSettings">
                    {{-- Header --}}
                    <div class="p-6 pb-4 border-b border-surface-200 dark:border-surface-800 bg-surface-50 dark:bg-surface-800/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center text-orange-600 dark:text-orange-400">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-surface-900 dark:text-white" id="auto-delete-modal-title">
                                        Auto Delete Settings
                                    </h3>
                                    <p class="text-sm text-surface-500 dark:text-surface-400">
                                        Konfigurasi pembersihan otomatis log.
                                    </p>
                                </div>
                            </div>
                            <button 
                                type="button"
                                @click="closeAutoDeleteModal()"
                                class="p-2 hover:bg-surface-200 dark:hover:bg-surface-700 rounded-xl transition-colors text-surface-500 hover:text-surface-700 dark:hover:text-surface-300"
                            >
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 space-y-6">
                        {{-- Enable Toggle --}}
                        <div class="flex items-center justify-between p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700">
                            <div>
                                <label class="block font-semibold text-surface-900 dark:text-white">Aktifkan Auto Delete</label>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">Sistem akan menghapus log lama secara otomatis.</p>
                            </div>
                            <button 
                                type="button"
                                @click="autoDeleteSettings.enabled = !autoDeleteSettings.enabled"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="autoDeleteSettings.enabled ? 'bg-theme-600' : 'bg-surface-300 dark:bg-surface-600'"
                                role="switch" 
                                :aria-checked="autoDeleteSettings.enabled"
                            >
                                <span 
                                    aria-hidden="true" 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="autoDeleteSettings.enabled ? 'translate-x-5' : 'translate-x-0'"
                                ></span>
                            </button>
                        </div>

                        {{-- Settings Form --}}
                        <div x-show="autoDeleteSettings.enabled" x-collapse>
                            <div class="space-y-5">
                                {{-- Retention Days --}}
                                <div>
                                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                        Hapus log yang lebih lama dari
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            x-model.number="autoDeleteSettings.retention_days"
                                            min="1"
                                            class="w-full pl-4 pr-16 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                        >
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-surface-500 font-medium">
                                            Hari
                                        </div>
                                    </div>
                                    <p class="text-xs text-surface-500 mt-1.5 flex items-center gap-1.5">
                                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                        Data log akan dihapus <strong>permanen</strong> setelah periode ini.
                                    </p>
                                </div>

                                <div class="h-px bg-surface-200 dark:bg-surface-700"></div>

                                {{-- Schedule Settings --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Frequency --}}
                                    <div>
                                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                            Frekuensi Pembersihan
                                        </label>
                                        <select 
                                            x-model="autoDeleteSettings.schedule"
                                            class="w-full px-3 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                        >
                                            <option value="daily">Setiap Hari</option>
                                            <option value="weekly">Setiap Minggu</option>
                                        </select>
                                    </div>

                                    {{-- Time --}}
                                    <div>
                                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                            Pada Jam
                                        </label>
                                        <input 
                                            type="time" 
                                            x-model="autoDeleteSettings.time"
                                            class="w-full px-3 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="p-6 pt-0 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            @click="closeAutoDeleteModal()"
                            class="px-5 py-2.5 rounded-xl border border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-300 font-medium hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-5 py-2.5 rounded-xl bg-orange-600 text-white font-medium hover:bg-orange-700 transition-colors shadow-lg shadow-orange-600/20 flex items-center gap-2"
                            :disabled="isSavingSettings"
                        >
                            <i x-show="isSavingSettings" class="fas fa-spinner fa-spin"></i>
                            <span x-text="isSavingSettings ? 'Menyimpan...' : 'Simpan Pengaturan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
