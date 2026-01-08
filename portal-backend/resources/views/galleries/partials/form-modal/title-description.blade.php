{{-- Title --}}
<div>
    <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
        Judul <span class="text-rose-500">*</span>
        <span x-show="formMode === 'create' && imageFiles.length > 1" class="text-xs text-surface-400 font-normal ml-2">(akan ditambahkan nomor urut)</span>
    </label>
    <input 
        type="text"
        x-model="formData.title"
        placeholder="Masukkan judul galeri"
        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
        :class="formErrors.title ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
    >
    <template x-if="formErrors.title">
        <p class="mt-1 text-xs text-rose-500" x-text="formErrors.title[0]"></p>
    </template>
</div>

{{-- Description --}}
<div>
    <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
        Deskripsi
    </label>
    <textarea 
        x-model="formData.description"
        rows="3"
        placeholder="Deskripsi kegiatan (opsional)"
        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all resize-none"
    ></textarea>
</div>
