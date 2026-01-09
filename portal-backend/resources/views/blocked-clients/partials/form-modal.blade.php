{{-- Form Modal --}}
<template x-teleport="body">
    <div 
        x-show="showFormModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        @keydown.escape.window="closeFormModal()"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showFormModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            @click="closeFormModal()"
        ></div>

        {{-- Modal Content --}}
        <div 
            x-show="showFormModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-lg bg-white dark:bg-surface-900 rounded-2xl shadow-2xl overflow-hidden"
        >
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-theme-50 dark:bg-theme-500/10 rounded-xl">
                        <i :data-lucide="formMode === 'create' ? 'shield-plus' : 'pencil'" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-white" x-text="formMode === 'create' ? 'Blokir IP Baru' : 'Edit Blokir IP'"></h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400" x-text="formMode === 'create' ? 'Tambahkan IP ke daftar blokir' : 'Perbarui informasi blokir'"></p>
                    </div>
                </div>
                <button 
                    @click="closeFormModal()"
                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-lg transition-colors"
                >
                    <i data-lucide="x" class="w-5 h-5 text-surface-500"></i>
                </button>
            </div>

            {{-- Form --}}
            <form @submit.prevent="submitForm()" class="p-6 space-y-5">
                {{-- IP Address --}}
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                        IP Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i data-lucide="globe" class="w-5 h-5 text-surface-400"></i>
                        </div>
                        <input 
                            type="text"
                            x-model="formData.ip_address"
                            :disabled="formMode === 'edit'"
                            placeholder="Contoh: 192.168.1.1"
                            class="w-full pl-11 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                            :class="formErrors.ip_address ? 'border-rose-500 focus:ring-rose-500' : ''"
                        >
                    </div>
                    <template x-if="formErrors.ip_address">
                        <p class="mt-1.5 text-sm text-rose-500" x-text="formErrors.ip_address[0]"></p>
                    </template>
                </div>

                {{-- Reason --}}
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                        Alasan Blokir <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        x-model="formData.reason"
                        rows="3"
                        placeholder="Jelaskan alasan memblokir IP ini..."
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all resize-none"
                        :class="formErrors.reason ? 'border-rose-500 focus:ring-rose-500' : ''"
                    ></textarea>
                    <template x-if="formErrors.reason">
                        <p class="mt-1.5 text-sm text-rose-500" x-text="formErrors.reason[0]"></p>
                    </template>
                </div>

                {{-- Duration Type --}}
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                        Durasi Blokir
                    </label>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <button 
                            type="button"
                            @click="formData.duration_type = 'temporary'"
                            class="p-3 rounded-xl border-2 text-sm font-medium transition-all flex items-center justify-center gap-2"
                            :class="formData.duration_type === 'temporary' 
                                ? 'border-theme-500 bg-theme-50 dark:bg-theme-500/10 text-theme-600 dark:text-theme-400'
                                : 'border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-400 hover:border-surface-300'"
                        >
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <span>Sementara</span>
                        </button>
                        <button 
                            type="button"
                            @click="formData.duration_type = 'permanent'"
                            class="p-3 rounded-xl border-2 text-sm font-medium transition-all flex items-center justify-center gap-2"
                            :class="formData.duration_type === 'permanent' 
                                ? 'border-rose-500 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400'
                                : 'border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-400 hover:border-surface-300'"
                        >
                            <i data-lucide="infinity" class="w-4 h-4"></i>
                            <span>Permanen</span>
                        </button>
                    </div>

                    {{-- Duration Input (only for temporary) --}}
                    <div x-show="formData.duration_type === 'temporary'" x-transition class="space-y-3">
                        <div class="flex items-center gap-3">
                            <input 
                                type="number"
                                x-model="formData.duration_value"
                                min="1"
                                placeholder="Durasi"
                                class="flex-1 px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                            >
                            <select 
                                x-model="formData.duration_unit"
                                class="px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                            >
                                <option value="minutes">Menit</option>
                                <option value="hours">Jam</option>
                                <option value="days">Hari</option>
                                <option value="weeks">Minggu</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-surface-500">
                            <i data-lucide="info" class="w-3.5 h-3.5"></i>
                            <span>IP akan otomatis di-unblock setelah durasi berakhir</span>
                        </div>
                    </div>

                    {{-- Permanent Warning --}}
                    <div x-show="formData.duration_type === 'permanent'" x-transition class="mt-3">
                        <div class="flex items-start gap-2 p-3 bg-rose-50 dark:bg-rose-500/10 rounded-xl">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-rose-600 dark:text-rose-400">
                                IP akan diblokir secara permanen dan harus di-unblock secara manual.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-surface-200 dark:border-surface-800">
                    <button 
                        type="button"
                        @click="closeFormModal()"
                        class="px-5 py-2.5 text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-colors"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="formLoading"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-theme-gradient text-white font-medium text-sm rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 disabled:opacity-50"
                    >
                        <template x-if="formLoading">
                            <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        </template>
                        <template x-if="!formLoading">
                            <i :data-lucide="formMode === 'create' ? 'shield-plus' : 'check'" class="w-4 h-4"></i>
                        </template>
                        <span x-text="formLoading ? 'Menyimpan...' : (formMode === 'create' ? 'Blokir IP' : 'Simpan Perubahan')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
