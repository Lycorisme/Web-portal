{{-- Detail Modal --}}
<template x-teleport="body">
    <div 
        x-show="showDetailModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showDetailModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="closeDetailModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
                @click.stop
            >
                {{-- Header with Theme Color --}}
                <template x-if="selectedItem">
                    <div>
                        <div class="px-6 py-5 bg-gradient-to-r from-theme-500 to-theme-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <i :data-lucide="selectedItem.media_type === 'video' ? 'video' : 'image'" class="w-6 h-6 text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white" x-text="selectedItem.title"></h3>
                                        <p class="text-sm text-white/80" x-text="selectedItem.album || 'Tanpa Album'"></p>
                                    </div>
                                </div>
                                <button @click="closeDetailModal()" class="p-2 rounded-xl hover:bg-white/20 transition-colors">
                                    <i data-lucide="x" class="w-5 h-5 text-white"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 space-y-5">
                            {{-- Image Preview --}}
                            <div class="rounded-xl overflow-hidden bg-surface-100 dark:bg-surface-800">
                                <template x-if="selectedItem.media_type === 'image' && selectedItem.image_url">
                                    <img :src="selectedItem.image_url" :alt="selectedItem.title" class="w-full max-h-80 object-contain">
                                </template>
                                <template x-if="selectedItem.media_type === 'video' && selectedItem.thumbnail_url">
                                    <div class="relative">
                                        <img :src="selectedItem.thumbnail_url" :alt="selectedItem.title" class="w-full max-h-80 object-contain">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                            <a :href="selectedItem.video_url" target="_blank" class="p-4 bg-white rounded-full shadow-lg hover:scale-110 transition-transform">
                                                <i data-lucide="play" class="w-8 h-8 text-surface-900"></i>
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Status Badges --}}
                            <div class="flex items-center gap-3 flex-wrap">
                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                    :class="selectedItem.is_published 
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' 
                                        : 'bg-surface-100 text-surface-500 dark:bg-surface-700 dark:text-surface-400'"
                                >
                                    <i :data-lucide="selectedItem.is_published ? 'check-circle' : 'circle-dashed'" class="w-4 h-4"></i>
                                    <span x-text="selectedItem.is_published ? 'Published' : 'Draft'"></span>
                                </span>
                                <template x-if="selectedItem.is_featured">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                        <i data-lucide="star" class="w-4 h-4"></i>
                                        <span>Featured</span>
                                    </span>
                                </template>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                    <i :data-lucide="selectedItem.media_type === 'video' ? 'video' : 'image'" class="w-4 h-4"></i>
                                    <span x-text="selectedItem.media_type === 'video' ? 'Video' : 'Gambar'"></span>
                                </span>
                            </div>

                            {{-- Description --}}
                            <template x-if="selectedItem.description">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Deskripsi</label>
                                    <p class="text-sm text-surface-700 dark:text-surface-300" x-text="selectedItem.description"></p>
                                </div>
                            </template>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Lokasi</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.location || '-'"></p>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Tanggal Event</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.event_date || '-'"></p>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Dibuat</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.created_at"></p>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Diupload Oleh</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.uploader || '-'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center gap-3">
                            <button 
                                @click="closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"
                            >
                                Tutup
                            </button>
                            <button 
                                @click="openEditModal(selectedItem); closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 flex items-center justify-center gap-2"
                            >
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                <span>Edit</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
