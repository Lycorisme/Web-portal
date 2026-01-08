{{-- Multiple Image Upload (for image type in create mode) --}}
<div x-show="formData.media_type === 'image'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50 space-y-4">
        <div class="flex items-center justify-between">
            <div class="text-center flex-1 space-y-2">
                <div class="inline-flex items-center justify-center p-3 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-2xl mb-2">
                    <i data-lucide="image-plus" class="w-6 h-6"></i>
                </div>
                <h4 class="text-base font-bold text-surface-900 dark:text-white">
                    <span x-show="formMode === 'create' || formMode === 'edit_group'">Upload Gambar (Multiple)</span>
                    <span x-show="formMode === 'edit'">Ganti Gambar</span>
                </h4>
                <p class="text-xs text-surface-500">PNG, JPG, WEBP (Maks 10MB per file, maks 20 gambar)</p>
            </div>
            {{-- Image Counter --}}
            <div x-show="imagePreviews.length > 0" class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-full text-sm font-semibold">
                    <span x-text="imagePreviews.length"></span> gambar
                </span>
                <button 
                    type="button" 
                    @click="clearAllImages()"
                    class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"
                    title="Hapus semua"
                >
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- Drop Zone --}}
        @include('galleries.partials.form-modal.image-dropzone')
        
        <template x-if="formErrors.image || formErrors.images">
            <p class="text-center text-sm font-medium text-rose-500" x-text="formErrors.image ? formErrors.image[0] : formErrors.images[0]"></p>
        </template>
    </div>
</div>
