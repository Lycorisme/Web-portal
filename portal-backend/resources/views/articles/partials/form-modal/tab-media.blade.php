{{-- Tab 2: Media (Thumbnail Upload) --}}
<div x-show="activeTab === 'media'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="bg-surface-50 dark:bg-surface-800/30 rounded-3xl p-4 sm:p-8 border border-surface-100 dark:border-surface-700/50 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-3 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-2xl mb-2">
                <i data-lucide="image-plus" class="w-8 h-8"></i>
            </div>
            <h4 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Assets Gambar</h4>
            <p class="text-xs sm:text-sm text-surface-500 max-w-sm mx-auto">Upload thumbnail berita.</p>
        </div>

        {{-- Drag & Drop Upload --}}
        <div 
            x-data="{ isDragging: false }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="
                isDragging = false;
                const file = $event.dataTransfer.files[0];
                if (file) {
                    formData.thumbnail = file;
                    formData.thumbnail_url = URL.createObjectURL(file);
                }
            "
            class="relative w-full aspect-video rounded-3xl border-3 border-dashed transition-all duration-500 ease-out overflow-hidden group"
            :class="isDragging 
                ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/10 scale-[1.02] shadow-2xl shadow-theme-500/10 ring-4 ring-theme-500/20' 
                : 'border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700'"
        >
            <input 
                type="file" 
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                accept="image/*"
                @change="
                    const file = $event.target.files[0];
                    if (file) {
                        formData.thumbnail = file;
                        formData.thumbnail_url = URL.createObjectURL(file);
                    }
                "
            >
            
            {{-- Empty State --}}
            <template x-if="!formData.thumbnail_url">
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300 text-center p-4" :class="isDragging ? 'scale-110' : 'scale-100'">
                    <div class="p-3 sm:p-4 rounded-full bg-white dark:bg-surface-700 shadow-sm mb-4">
                        <i data-lucide="upload-cloud" class="w-6 h-6 sm:w-8 sm:h-8 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
                    </div>
                    <p class="text-sm font-semibold text-surface-700 dark:text-surface-300">
                        <span class="text-theme-600 dark:text-theme-400">Klik Upload</span> / Drop
                    </p>
                </div>
            </template>

            {{-- Preview --}}
            <template x-if="formData.thumbnail_url">
                <div class="absolute inset-0 w-full h-full bg-black/5">
                    <img :src="formData.thumbnail_url" class="absolute inset-0 w-full h-full object-cover">
                    
                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-6 text-white z-20 pointer-events-none">
                        <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-lg"></i>
                        <button type="button" @click.stop.prevent="window.open(formData.thumbnail_url)" class="pointer-events-auto mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-full text-xs font-medium backdrop-blur-sm transition-colors border border-white/50">
                            Lihat Fullsize
                        </button>
                    </div>

                    <button 
                        type="button" 
                        @click.stop.prevent="formData.thumbnail = null; formData.thumbnail_url = ''"
                        class="absolute top-4 right-4 p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all z-30 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 hover:scale-110"
                    >
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </template>
        </div>
        <template x-if="formErrors.thumbnail">
            <p class="text-center text-sm font-medium text-rose-500" x-text="formErrors.thumbnail[0]"></p>
        </template>
    </div>
</div>
