{{-- Modal Header --}}
<div class="bg-white/80 dark:bg-surface-900/80 backdrop-blur-md border-b border-surface-200/50 dark:border-surface-700/50 px-4 sm:px-8 py-4 sm:py-5 flex-shrink-0 flex items-center justify-between z-20">
    <div class="flex items-center gap-3 sm:gap-4">
        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-theme-600 text-white flex items-center justify-center shadow-lg shadow-theme-500/20 shrink-0">
            <i :data-lucide="formMode === 'create' ? 'plus' : 'pen-line'" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        </div>
        <div>
            <h3 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white tracking-tight leading-tight" x-text="formMode === 'create' ? 'Buat Berita Baru' : 'Edit Berita'"></h3>
            <p class="hidden sm:block text-sm text-surface-500 dark:text-surface-400 font-medium">Manajamen konten artikel & publikasi</p>
        </div>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
        <template x-if="injectionDetected">
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-500/10 dark:bg-rose-500/20 border border-rose-300 dark:border-rose-700 mr-2 animate-pulse">
                <i data-lucide="shield-x" class="w-4 h-4 text-rose-500"></i>
                <span class="text-xs font-bold text-rose-600 dark:text-rose-400" x-text="detectedThreats.length + ' Ancaman Terdeteksi'"></span>
            </div>
        </template>
        <button type="button" @click="closeFormModal()" class="px-3 py-2 sm:px-4 sm:py-2.5 text-xs sm:text-sm font-semibold text-surface-500 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-all">Batal</button>
        
        {{-- Submit Button --}}
        <div class="relative group/submit">
            <button type="submit" form="articleForm"
                :disabled="formLoading || injectionDetected || !formData.title || formData.title.length < 3"
                :class="{
                    'bg-theme-600 hover:bg-theme-500 shadow-theme-500/30 hover:shadow-theme-500/50 hover:scale-[1.02]': !formLoading && !injectionDetected && formData.title && formData.title.length >= 3,
                    'bg-surface-400 dark:bg-surface-600 cursor-not-allowed': formLoading || injectionDetected || !formData.title || formData.title.length < 3
                }"
                class="relative overflow-hidden px-4 py-2 sm:px-6 sm:py-2.5 text-white font-bold rounded-xl shadow-lg active:scale-95 transition-all duration-300 flex items-center justify-center gap-2 text-xs sm:text-sm">
                <div x-show="formLoading" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                <i x-show="injectionDetected && !formLoading" data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                <span x-text="formMode === 'create' ? 'Terbitkan' : 'Simpan'"></span>
                <div x-show="!formLoading && !injectionDetected && formData.title && formData.title.length >= 3" class="absolute inset-0 -translate-x-[100%] group-hover/submit:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent z-10"></div>
            </button>
        </div>
    </div>
</div>
