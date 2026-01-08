{{-- Gallery Grid Section --}}
<div class="relative p-4 sm:p-6" style="overflow: visible;">
    {{-- Loading Overlay --}}
    <div 
        x-show="loading"
        x-transition
        class="absolute inset-0 bg-white/80 dark:bg-surface-900/80 backdrop-blur-sm flex items-center justify-center z-20"
    >
        <div class="flex flex-col items-center gap-3">
            <div class="w-10 h-10 border-4 border-theme-500/30 border-t-theme-500 rounded-full animate-spin"></div>
            <span class="text-sm text-surface-600 dark:text-surface-400">Memuat galeri...</span>
        </div>
    </div>

    {{-- Grid View --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        <template x-for="item in galleries" :key="item.id">
            <div 
                class="gallery-card group relative bg-white dark:bg-surface-800 rounded-2xl border overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-theme-500/10 hover:-translate-y-1"
                :class="item.deleted_at 
                    ? 'border-rose-200 dark:border-rose-800/50 opacity-75' 
                    : 'border-surface-200/50 dark:border-surface-700/50'"
            >
                {{-- Image Container --}}
                <div class="relative aspect-[4/3] overflow-hidden bg-surface-100 dark:bg-surface-700">
                    {{-- Checkbox --}}
                    <div class="absolute top-3 left-3 z-10">
                        <input 
                            type="checkbox"
                            :value="item.id"
                            x-model="selectedIds"
                            class="w-5 h-5 rounded-lg border-2 border-white/50 bg-white/50 backdrop-blur-sm text-theme-600 focus:ring-theme-500 focus:ring-offset-0 cursor-pointer transition-all opacity-0 group-hover:opacity-100"
                            :class="{'opacity-100': selectedIds.includes(item.id)}"
                        >
                    </div>

                    {{-- Media Type Badge --}}
                    <div class="absolute top-3 right-3 z-10">
                        <span 
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold backdrop-blur-sm"
                            :class="item.media_type === 'video' 
                                ? 'bg-rose-500/90 text-white' 
                                : 'bg-white/90 dark:bg-surface-800/90 text-surface-700 dark:text-surface-300'"
                        >
                            <i :data-lucide="item.media_type === 'video' ? 'play' : 'image'" class="w-3 h-3"></i>
                            <span x-text="item.media_type === 'video' ? 'Video' : 'Gambar'"></span>
                        </span>
                    </div>

                    {{-- Featured Badge --}}
                    <template x-if="item.is_featured">
                        <div class="absolute top-12 right-3 z-10">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-amber-500/90 text-white backdrop-blur-sm">
                                <i data-lucide="star" class="w-3 h-3"></i>
                                Featured
                            </span>
                        </div>
                    </template>

                    {{-- Image/Thumbnail --}}
                    <template x-if="item.thumbnail_url || item.image_url">
                        <img 
                            :src="item.thumbnail_url || item.image_url" 
                            :alt="item.title"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            loading="lazy"
                        >
                    </template>
                    <template x-if="!item.thumbnail_url && !item.image_url">
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image-off" class="w-12 h-12 text-surface-400"></i>
                        </div>
                    </template>

                    {{-- Video Play Overlay --}}
                    <template x-if="item.media_type === 'video'">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-14 h-14 rounded-full bg-white/90 flex items-center justify-center shadow-lg">
                                <i data-lucide="play" class="w-6 h-6 text-surface-900 ml-1"></i>
                            </div>
                        </div>
                    </template>

                    {{-- Quick Actions Overlay --}}
                    <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/60 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex items-center justify-center gap-2">
                            <button 
                                @click="openPreview(item)"
                                class="p-2 bg-white/90 rounded-lg hover:bg-white transition-colors"
                                title="Preview"
                            >
                                <i data-lucide="eye" class="w-4 h-4 text-surface-700"></i>
                            </button>
                            <button 
                                @click="openEditModal(item)"
                                x-show="!item.deleted_at"
                                class="p-2 bg-white/90 rounded-lg hover:bg-white transition-colors"
                                title="Edit"
                            >
                                <i data-lucide="pencil" class="w-4 h-4 text-surface-700"></i>
                            </button>
                            <button 
                                @click="openMenu(item, $event)"
                                class="p-2 bg-white/90 rounded-lg hover:bg-white transition-colors"
                                title="Opsi Lainnya"
                            >
                                <i data-lucide="more-vertical" class="w-4 h-4 text-surface-700"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Deleted Overlay --}}
                    <template x-if="item.deleted_at">
                        <div class="absolute inset-0 bg-rose-500/10 flex items-center justify-center">
                            <span class="px-3 py-1.5 bg-rose-500 text-white text-xs font-semibold rounded-lg">
                                Dihapus
                            </span>
                        </div>
                    </template>
                </div>

                {{-- Content --}}
                <div class="p-4">
                    {{-- Title --}}
                    <h3 
                        class="text-sm font-semibold text-surface-900 dark:text-white mb-1 line-clamp-1"
                        :class="{'line-through opacity-60': item.deleted_at}"
                        x-text="item.title"
                    ></h3>

                    {{-- Album & Date --}}
                    <div class="flex items-center gap-2 text-xs text-surface-500 dark:text-surface-400 mb-2">
                        <template x-if="item.album">
                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="folder" class="w-3 h-3"></i>
                                <span x-text="item.album" class="truncate max-w-[80px]"></span>
                            </span>
                        </template>
                        <template x-if="item.event_date">
                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                <span x-text="item.event_date"></span>
                            </span>
                        </template>
                    </div>

                    {{-- Status Badges --}}
                    <div class="flex items-center gap-2">
                        <span 
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                            :class="item.is_published 
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' 
                                : 'bg-surface-100 text-surface-500 dark:bg-surface-700 dark:text-surface-400'"
                        >
                            <i :data-lucide="item.is_published ? 'check-circle' : 'circle-dashed'" class="w-3 h-3"></i>
                            <span x-text="item.is_published ? 'Published' : 'Draft'"></span>
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Empty State --}}
    <template x-if="!loading && galleries.length === 0">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="images" class="w-10 h-10 text-surface-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-1">Tidak Ada Galeri</h3>
            <p class="text-sm text-surface-500 dark:text-surface-400 mb-4">Belum ada item galeri yang tersedia.</p>
            <button 
                @click="openCreateModal()"
                x-show="!showTrash"
                class="inline-flex items-center gap-2 px-4 py-2 bg-theme-gradient text-white font-medium text-sm rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20"
            >
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Galeri Pertama</span>
            </button>
        </div>
    </template>
</div>
