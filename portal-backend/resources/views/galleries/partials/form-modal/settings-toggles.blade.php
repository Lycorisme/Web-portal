{{-- Status Toggles --}}
<div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50">
    <h4 class="text-sm font-semibold text-surface-700 dark:text-surface-300 mb-4">Pengaturan</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Is Published --}}
        <div class="flex items-center gap-3 p-3 bg-white dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
            <button 
                type="button" 
                @click="formData.is_published = !formData.is_published"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                :class="formData.is_published ? 'bg-theme-500' : 'bg-surface-300 dark:bg-surface-600'"
            >
                <span 
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    :class="formData.is_published ? 'translate-x-5' : 'translate-x-0'"
                ></span>
            </button>
            <div>
                <span class="text-sm font-medium text-surface-700 dark:text-surface-300" x-text="formData.is_published ? 'Published' : 'Draft'"></span>
                <p class="text-xs text-surface-500">Tampilkan di website</p>
            </div>
        </div>

        {{-- Is Featured --}}
        <div class="flex items-center gap-3 p-3 bg-white dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
            <button 
                type="button" 
                @click="formData.is_featured = !formData.is_featured"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                :class="formData.is_featured ? 'bg-amber-500' : 'bg-surface-300 dark:bg-surface-600'"
            >
                <span 
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    :class="formData.is_featured ? 'translate-x-5' : 'translate-x-0'"
                ></span>
            </button>
            <div>
                <span class="text-sm font-medium text-surface-700 dark:text-surface-300" x-text="formData.is_featured ? 'Featured' : 'Normal'"></span>
                <p class="text-xs text-surface-500">Tampilkan sebagai unggulan</p>
            </div>
        </div>
    </div>
</div>
