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
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl flex flex-col"
                @click.stop
            >
                {{-- Content when selectedArticle exists --}}
                <div x-show="selectedArticle" class="flex flex-col">
                    {{-- Thumbnail/Header Area --}}
                    <div class="relative h-48 bg-gradient-to-br from-theme-500 to-theme-600">
                        {{-- Thumbnail Image --}}
                        <img 
                            x-show="selectedArticle?.thumbnail"
                            :src="selectedArticle?.thumbnail" 
                            :alt="selectedArticle?.title" 
                            class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-700"
                            @click="window.open(selectedArticle?.thumbnail, '_blank')"
                        >
                        
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        
                        {{-- Close Button --}}
                        <button 
                            @click="closeDetailModal()" 
                            class="absolute top-4 right-4 p-2 rounded-xl bg-black/30 hover:bg-black/50 backdrop-blur-sm transition-colors"
                        >
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>

                        {{-- Title & Category --}}
                        <div class="absolute bottom-4 left-6 right-6">
                            <span 
                                x-show="selectedArticle?.category_name"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold mb-2"
                                :style="`background-color: ${selectedArticle?.category_color}; color: white`"
                            >
                                <i :data-lucide="selectedArticle?.category_icon || 'folder'" class="w-3 h-3"></i>
                                <span x-text="selectedArticle?.category_name"></span>
                            </span>
                            <h3 class="text-xl font-bold text-white leading-tight" x-text="selectedArticle?.title"></h3>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 space-y-5">
                        {{-- Meta Info Row --}}
                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Status Badge --}}
                            <span 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                :class="getStatusColor(selectedArticle?.status)"
                            >
                                <span x-text="getStatusLabel(selectedArticle?.status)"></span>
                            </span>
                            
                            {{-- Views --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                <span x-text="(selectedArticle?.views || 0) + ' views'"></span>
                            </span>

                            {{-- Read Time --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-300">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                <span x-text="(selectedArticle?.read_time || 1) + ' menit baca'"></span>
                            </span>
                        </div>

                        {{-- Author Info --}}
                        <div class="flex items-center gap-3 p-3 bg-surface-50 dark:bg-surface-800 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center overflow-hidden">
                                <img 
                                    x-show="selectedArticle?.author_avatar"
                                    :src="selectedArticle?.author_avatar" 
                                    :alt="selectedArticle?.author_name" 
                                    class="w-full h-full object-cover"
                                >
                                <span 
                                    x-show="!selectedArticle?.author_avatar"
                                    class="text-sm font-bold text-theme-600 dark:text-theme-400" 
                                    x-text="(selectedArticle?.author_name || 'A').charAt(0).toUpperCase()"
                                ></span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedArticle?.author_name || 'Admin'"></p>
                                <p class="text-xs text-surface-500" x-text="'Dipublikasikan ' + (selectedArticle?.published_at || selectedArticle?.created_at)"></p>
                            </div>
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">URL</label>
                            <code class="block text-sm bg-surface-100 dark:bg-surface-800 px-3 py-2 rounded-lg font-mono text-surface-700 dark:text-surface-300" x-text="'/berita/' + selectedArticle?.slug"></code>
                        </div>

                        {{-- Excerpt --}}
                        <div x-show="selectedArticle?.excerpt">
                            <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Ringkasan</label>
                            <p class="text-sm text-surface-700 dark:text-surface-300 leading-relaxed" x-text="selectedArticle?.excerpt"></p>
                        </div>

                        {{-- Content Preview --}}
                        <div x-show="selectedArticle?.content">
                            <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Konten</label>
                            <div class="text-sm text-surface-700 dark:text-surface-300 leading-relaxed max-h-40 overflow-y-auto p-3 bg-surface-50 dark:bg-surface-800 rounded-xl" x-html="selectedArticle?.content?.substring(0, 500) + (selectedArticle?.content?.length > 500 ? '...' : '')"></div>
                        </div>

                        {{-- Timestamps --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Dibuat</label>
                                <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedArticle?.created_at"></p>
                            </div>
                            <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Terakhir Update</label>
                                <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedArticle?.updated_at"></p>
                            </div>
                        </div>

                        {{-- SEO Info --}}
                        <div x-show="selectedArticle?.meta_title || selectedArticle?.meta_description || selectedArticle?.meta_keywords" class="border-t border-surface-200 dark:border-surface-700 pt-4">
                            <h4 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                                <i data-lucide="search" class="w-4 h-4 text-theme-500"></i>
                                Informasi SEO
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div x-show="selectedArticle?.meta_title">
                                    <label class="block text-xs text-surface-500 mb-1">Meta Title</label>
                                    <p class="text-surface-700 dark:text-surface-300" x-text="selectedArticle?.meta_title"></p>
                                </div>
                                <div x-show="selectedArticle?.meta_description">
                                    <label class="block text-xs text-surface-500 mb-1">Meta Description</label>
                                    <p class="text-surface-700 dark:text-surface-300" x-text="selectedArticle?.meta_description"></p>
                                </div>
                                <div x-show="selectedArticle?.meta_keywords">
                                    <label class="block text-xs text-surface-500 mb-1">Keywords</label>
                                    <p class="text-surface-700 dark:text-surface-300" x-text="selectedArticle?.meta_keywords"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center gap-3 flex-shrink-0">
                        <button 
                            @click="closeDetailModal()"
                            class="flex-1 px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"
                        >
                            Tutup
                        </button>
                        <button 
                            @click="openEditModal(selectedArticle); closeDetailModal()"
                            class="flex-1 px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 flex items-center justify-center gap-2"
                        >
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            <span>Edit</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
