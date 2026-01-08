{{-- Album & Location --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    {{-- Album with Autocomplete --}}
    <div class="album-autocomplete relative">
        <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
            Album
        </label>
        <div class="relative">
            <input 
                type="text"
                x-model="formData.album"
                @focus="showAlbumDropdown = true"
                @input="showAlbumDropdown = true"
                placeholder="Nama album/event"
                autocomplete="off"
                class="w-full px-4 py-3 pr-10 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
            >
            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400">
                <i data-lucide="folder" class="w-4 h-4"></i>
            </div>
        </div>
        
        {{-- Album Dropdown --}}
        <div 
            x-show="showAlbumDropdown && filteredAlbums.length > 0"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="absolute z-30 left-0 right-0 mt-1 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl shadow-xl overflow-hidden max-h-48 overflow-y-auto"
        >
            <template x-for="album in filteredAlbums" :key="album">
                <button 
                    type="button"
                    @click="selectAlbum(album)"
                    class="w-full px-4 py-2.5 text-left text-sm hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors flex items-center gap-2"
                    :class="formData.album === album ? 'bg-theme-50 dark:bg-theme-900/20 text-theme-600 dark:text-theme-400' : 'text-surface-700 dark:text-surface-300'"
                >
                    <i data-lucide="folder" class="w-4 h-4 opacity-60"></i>
                    <span x-text="album"></span>
                </button>
            </template>
        </div>
    </div>
    
    {{-- Location --}}
    <div>
        <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
            Lokasi
        </label>
        <input 
            type="text"
            x-model="formData.location"
            placeholder="Lokasi kegiatan"
            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
        >
    </div>
</div>
