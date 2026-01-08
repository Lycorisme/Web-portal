{{-- Drop Zone --}}
<div 
    x-data="{ isDragging: false }"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="
        isDragging = false;
        const files = $event.dataTransfer.files;
        if (files.length) {
            if (formMode === 'edit') {
                handleSingleImageUpload({ target: { files: [files[0]] } });
            } else {
                handleMultipleImageUpload({ target: { files } });
            }
        }
    "
    class="relative w-full rounded-2xl border-3 border-dashed transition-all duration-500 ease-out overflow-hidden group"
    :class="isDragging 
        ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/10 scale-[1.02] shadow-2xl shadow-theme-500/10 ring-4 ring-theme-500/20' 
        : (formErrors.image || formErrors.images ? 'border-rose-400 bg-rose-50 dark:bg-rose-900/10' : 'border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700')"
>
    <input 
        type="file" 
        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
        accept="image/*"
        :multiple="formMode === 'create' || formMode === 'edit_group'"
        @change="formMode === 'edit' ? handleSingleImageUpload($event) : handleMultipleImageUpload($event)"
    >
    
    {{-- Empty State --}}
    <div x-show="imagePreviews.length === 0" class="py-12 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300 text-center p-4" :class="isDragging ? 'scale-110' : 'scale-100'">
        <div class="p-3 sm:p-4 rounded-full bg-white dark:bg-surface-700 shadow-sm mb-4">
            <i data-lucide="upload-cloud" class="w-6 h-6 sm:w-8 sm:h-8 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
        </div>
        <p class="text-sm font-semibold text-surface-700 dark:text-surface-300">
            <span class="text-theme-600 dark:text-theme-400">Klik Upload</span> atau Drag & Drop
        </p>
        <p x-show="formMode === 'create' || formMode === 'edit_group'" class="text-xs text-surface-400 mt-1">Pilih beberapa gambar sekaligus</p>
    </div>

    {{-- Preview Grid --}}
    <div x-show="imagePreviews.length > 0" class="p-4">
        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
            <template x-for="(preview, index) in imagePreviews" :key="index">
                <div class="relative aspect-square rounded-xl overflow-hidden group/item bg-surface-200 dark:bg-surface-700">
                    <img :src="preview.url" class="w-full h-full object-cover">
                    {{-- Remove Button --}}
                    <button 
                        type="button" 
                        @click.stop.prevent="removeImageAt(index)"
                        class="absolute top-2 right-2 p-1.5 bg-rose-500 text-white rounded-lg shadow-lg hover:bg-rose-600 transition-all z-20 opacity-0 group-hover/item:opacity-100 hover:scale-110"
                    >
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                    {{-- Index Badge --}}
                    <div class="absolute bottom-2 left-2 px-2 py-0.5 bg-black/50 text-white text-xs rounded-md font-medium">
                        <span x-text="index + 1"></span>
                    </div>
                </div>
            </template>
            
            {{-- Add More Button (create/edit_group mode) --}}
            <div x-show="(formMode === 'create' || formMode === 'edit_group') && imagePreviews.length < 20" class="relative aspect-square rounded-xl border-2 border-dashed border-surface-300 dark:border-surface-600 flex items-center justify-center hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700 transition-all cursor-pointer group/add">
                <input 
                    type="file" 
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    accept="image/*"
                    multiple
                    @change="handleMultipleImageUpload($event)"
                >
                <div class="flex flex-col items-center gap-1 pointer-events-none">
                    <i data-lucide="plus" class="w-6 h-6 text-surface-400 group-hover/add:text-theme-500 transition-colors"></i>
                    <span class="text-xs text-surface-400 group-hover/add:text-theme-500">Tambah</span>
                </div>
            </div>
        </div>
    </div>
</div>
