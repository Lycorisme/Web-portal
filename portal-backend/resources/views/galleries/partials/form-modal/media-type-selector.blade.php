{{-- Media Type Selector (only in create mode) --}}
<div x-show="formMode === 'create'" class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50">
    <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-3">
        Tipe Media <span class="text-rose-500">*</span>
    </label>
    <div class="grid grid-cols-2 gap-3">
        <button 
            type="button"
            @click="formData.media_type = 'image'; imageFiles = []; imagePreviews = [];"
            class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all duration-300"
            :class="formData.media_type === 'image' 
                ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20 scale-[1.02] shadow-lg shadow-theme-500/10' 
                : 'border-surface-200 dark:border-surface-700 hover:border-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800'"
        >
            <i data-lucide="images" class="w-8 h-8 transition-colors" :class="formData.media_type === 'image' ? 'text-theme-500' : 'text-surface-400'"></i>
            <span class="text-sm font-medium transition-colors" :class="formData.media_type === 'image' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Gambar</span>
            <span class="text-xs text-surface-400" x-show="formData.media_type === 'image'">Multiple upload</span>
        </button>
        <button 
            type="button"
            @click="formData.media_type = 'video'; imageFiles = []; imagePreviews = [];"
            class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all duration-300"
            :class="formData.media_type === 'video' 
                ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20 scale-[1.02] shadow-lg shadow-theme-500/10' 
                : 'border-surface-200 dark:border-surface-700 hover:border-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800'"
        >
            <i data-lucide="video" class="w-8 h-8 transition-colors" :class="formData.media_type === 'video' ? 'text-theme-500' : 'text-surface-400'"></i>
            <span class="text-sm font-medium transition-colors" :class="formData.media_type === 'video' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Video</span>
        </button>
    </div>
</div>
