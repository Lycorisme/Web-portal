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
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity"
            @click="closeDetailModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-end sm:items-center justify-center sm:p-4">
            <div 
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-2xl sm:max-h-[85vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                {{-- Header --}}
                <template x-if="selectedItem">
                    <div class="flex flex-col h-full overflow-hidden">
                        {{-- Header Bar --}}
                        <div class="bg-white/80 dark:bg-surface-900/80 backdrop-blur-md border-b border-surface-200/50 dark:border-surface-700/50 px-4 sm:px-6 py-4 flex-shrink-0 flex items-center justify-between z-20">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl text-white flex items-center justify-center shadow-lg shrink-0"
                                     :class="selectedItem.media_type === 'video' ? 'bg-rose-600 shadow-rose-500/20' : 'bg-theme-600 shadow-theme-500/20'">
                                    <i :data-lucide="selectedItem.media_type === 'video' ? 'video' : 'image'" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white tracking-tight leading-tight truncate" x-text="selectedItem.title"></h3>
                                    <p class="text-sm text-surface-500 dark:text-surface-400 font-medium truncate" x-text="selectedItem.album || 'Tanpa Album'"></p>
                                </div>
                            </div>
                            <button @click="closeDetailModal()" class="p-2 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors flex-shrink-0">
                                <i data-lucide="x" class="w-5 h-5 text-surface-500"></i>
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="p-4 sm:p-6 space-y-5 overflow-y-auto flex-1 min-h-0">
                            {{-- Image/Video Preview --}}
                            <div class="rounded-2xl overflow-hidden bg-surface-100 dark:bg-surface-800 shadow-inner">
                                <template x-if="selectedItem.media_type === 'image' && selectedItem.image_url">
                                    <div class="relative group cursor-pointer" @click="openPreview(selectedItem); closeDetailModal()">
                                        <img :src="selectedItem.image_url" :alt="selectedItem.title" class="w-full max-h-80 object-contain transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <div class="p-3 bg-white/90 rounded-full">
                                                <i data-lucide="maximize" class="w-5 h-5 text-surface-700"></i>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="selectedItem.media_type === 'video' && selectedItem.thumbnail_url">
                                    <div class="relative group cursor-pointer" @click="openPreview(selectedItem); closeDetailModal()">
                                        <img :src="selectedItem.thumbnail_url" :alt="selectedItem.title" class="w-full max-h-80 object-contain">
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center transition-all group-hover:bg-black/40">
                                            <div class="p-4 bg-white rounded-full shadow-lg transform transition-transform duration-300 group-hover:scale-110">
                                                <i data-lucide="play" class="w-8 h-8 text-rose-600 ml-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Status Badges --}}
                            <div class="flex items-center gap-3 flex-wrap">
                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold transition-colors"
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                      :class="selectedItem.media_type === 'video' ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'">
                                    <i :data-lucide="selectedItem.media_type === 'video' ? 'video' : 'image'" class="w-4 h-4"></i>
                                    <span x-text="selectedItem.media_type === 'video' ? 'Video' : 'Gambar'"></span>
                                </span>
                            </div>

                            {{-- Description --}}
                            <template x-if="selectedItem.description">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-100 dark:border-surface-700/50">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Deskripsi</label>
                                    <p class="text-sm text-surface-700 dark:text-surface-300 leading-relaxed" x-text="selectedItem.description"></p>
                                </div>
                            </template>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-100 dark:border-surface-700/50">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Lokasi</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.location || '-'"></p>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-100 dark:border-surface-700/50">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Tanggal Event</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.event_date || '-'"></p>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-100 dark:border-surface-700/50">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Dibuat</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.created_at"></p>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-100 dark:border-surface-700/50">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Diupload Oleh</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedItem.uploader || '-'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="px-4 sm:px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200/50 dark:border-surface-700/50 flex items-center gap-3 flex-shrink-0">
                            <button 
                                @click="closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 font-semibold rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"
                            >
                                Tutup
                            </button>
                            <button 
                                @click="openEditModal(selectedItem); closeDetailModal()"
                                x-show="!selectedItem.deleted_at"
                                class="flex-1 px-4 py-2.5 bg-theme-600 hover:bg-theme-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-theme-500/20 flex items-center justify-center gap-2"
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
