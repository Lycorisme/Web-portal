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
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-md"
            @click="closeDetailModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
            <div 
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-2xl bg-white dark:bg-surface-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                @click.stop
            >
                {{-- Content when selectedArticle exists --}}
                <div x-show="selectedArticle" class="flex flex-col bg-white dark:bg-surface-900">
                    
                    {{-- Header with Thumbnail --}}
                    <div class="relative h-56 sm:h-64 flex-shrink-0 group overflow-hidden">
                        {{-- Image Background --}}
                        <div class="absolute inset-0 bg-surface-200 dark:bg-surface-800 animate-pulse" x-show="!selectedArticle?.thumbnail"></div>
                        <img 
                            x-show="selectedArticle?.thumbnail"
                            :src="selectedArticle?.thumbnail" 
                            :alt="selectedArticle?.title" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        >
                        
                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-900/90 via-surface-900/40 to-transparent"></div>
                        
                        {{-- Top Actions --}}
                        <div class="absolute top-4 right-4 z-10">
                            <button 
                                @click="closeDetailModal()" 
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-black/20 text-white/80 hover:bg-black/40 hover:text-white backdrop-blur-md transition-all border border-white/10"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        {{-- Title & Category Info --}}
                        <div class="absolute bottom-0 left-0 right-0 p-6 pt-12">
                            <div class="flex items-center gap-3 mb-3">
                                <span 
                                    x-show="selectedArticle?.category_name"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold backdrop-blur-md shadow-sm border border-white/10 text-white"
                                    :style="`background-color: ${selectedArticle?.category_color}CC`"
                                >
                                    <i :data-lucide="selectedArticle?.category_icon || 'folder'" class="w-3 h-3"></i>
                                    <span x-text="selectedArticle?.category_name"></span>
                                </span>
                                
                                <div class="flex items-center gap-2 text-white/80 text-xs font-medium backdrop-blur-md px-2 py-1 rounded-full bg-black/20 border border-white/10">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        <span x-text="selectedArticle?.views || 0"></span>
                                    </span>
                                    <span class="w-px h-3 bg-white/20"></span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span x-text="(selectedArticle?.read_time || 1) + ' min'"></span>
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-2xl sm:text-3xl font-bold text-white leading-tight tracking-tight drop-shadow-sm" x-text="selectedArticle?.title"></h3>
                        </div>
                    </div>

                    {{-- Scrollable Content (Window Scroll) --}}
                    <div>
                        <div class="p-6 space-y-8">
                            
                            {{-- Author & Status Bar --}}
                            <div class="flex items-center justify-between py-4 border-b border-surface-100 dark:border-surface-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-br from-theme-400 to-theme-600 ring-2 ring-surface-50 dark:ring-surface-800">
                                        <div class="w-full h-full rounded-full bg-surface-100 dark:bg-surface-800 overflow-hidden">
                                            <img 
                                                x-show="selectedArticle?.author_avatar"
                                                :src="selectedArticle?.author_avatar" 
                                                :alt="selectedArticle?.author_name" 
                                                class="w-full h-full object-cover"
                                            >
                                            <div 
                                                x-show="!selectedArticle?.author_avatar"
                                                class="w-full h-full flex items-center justify-center bg-surface-200 dark:bg-surface-700 text-surface-500"
                                            >
                                                <span class="text-xs font-bold" x-text="(selectedArticle?.author_name || 'A').charAt(0).toUpperCase()"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-surface-900 dark:text-white" x-text="selectedArticle?.author_name || 'Admin'"></p>
                                        <p class="text-xs text-surface-500 font-medium" x-text="selectedArticle?.published_at || selectedArticle?.created_at"></p>
                                    </div>
                                </div>

                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset"
                                    :class="getStatusColor(selectedArticle?.status) + ' bg-opacity-10'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    <span x-text="getStatusLabel(selectedArticle?.status)"></span>
                                </span>
                            </div>

                            {{-- Main Content Section --}}
                            <div class="space-y-6">
                                {{-- Excerpt --}}
                                <div x-show="selectedArticle?.excerpt" class="relative pl-4 border-l-2 border-theme-500">
                                    <p class="text-base font-medium text-surface-800 dark:text-surface-200 italic leading-relaxed" x-text="selectedArticle?.excerpt"></p>
                                </div>

                                {{-- URL --}}
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-bold text-surface-400 dark:text-surface-500 uppercase tracking-widest">Public URL</label>
                                        <button 
                                            @click="navigator.clipboard.writeText('/berita/' + selectedArticle?.slug)" 
                                            class="text-xs font-medium text-theme-600 dark:text-theme-400 hover:text-theme-700 flex items-center gap-1 transition-colors"
                                        >
                                            <i data-lucide="copy" class="w-3 h-3"></i> Copy
                                        </button>
                                    </div>
                                    <div class="p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-100 dark:border-surface-700 font-mono text-sm text-surface-600 dark:text-surface-300 break-all select-all">
                                        <span class="text-surface-400 select-none">/berita/</span><span x-text="selectedArticle?.slug"></span>
                                    </div>
                                </div>

                                {{-- Meta Grid --}}
                                <div class="grid grid-cols-2 gap-4">
                                     <div class="p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50">
                                        <p class="text-xs text-surface-400 font-medium uppercase tracking-wider mb-1">Dibuat</p>
                                        <p class="text-sm font-semibold text-surface-900 dark:text-white" x-text="selectedArticle?.created_at"></p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50">
                                        <p class="text-xs text-surface-400 font-medium uppercase tracking-wider mb-1">Diperbarui</p>
                                        <p class="text-sm font-semibold text-surface-900 dark:text-white" x-text="selectedArticle?.updated_at"></p>
                                    </div>
                                </div>

                                {{-- SEO Section --}}
                                <div x-show="selectedArticle?.meta_title || selectedArticle?.meta_description" class="space-y-3 pt-6 border-t border-surface-100 dark:border-surface-800">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                                            <i data-lucide="search" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-bold text-surface-900 dark:text-white">SEO Preview</span>
                                    </div>
                                    
                                    <div class="p-4 rounded-xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-900">
                                        <p class="text-sm text-[#1a0dab] dark:text-[#8ab4f8] font-medium hover:underline truncate cursor-pointer" x-text="selectedArticle?.meta_title || selectedArticle?.title"></p>
                                        <p class="text-xs text-[#006621] dark:text-[#00a74a] truncate mt-0.5">https://example.com/berita/<span x-text="selectedArticle?.slug"></span></p>
                                        <p class="text-sm text-surface-600 dark:text-surface-300 mt-1 line-clamp-2" x-text="selectedArticle?.meta_description || selectedArticle?.excerpt"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Comments Preview --}}
                            <div class="pt-8 border-t border-surface-100 dark:border-surface-800">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-lg font-bold text-surface-900 dark:text-white">Komentar Terakhir</h4>
                                        <span class="px-2 py-0.5 rounded-full bg-surface-100 dark:bg-surface-800 text-xs font-bold text-surface-600 dark:text-surface-400" x-text="detailComments?.length || 0"></span>
                                    </div>
                                    <button 
                                        @click="fetchDetailComments(selectedArticle.id)"
                                        class="p-2 text-surface-400 hover:text-theme-600 hover:bg-theme-50 dark:hover:bg-theme-900/20 rounded-lg transition-all"
                                    >
                                        <i data-lucide="refresh-cw" class="w-4 h-4" :class="detailCommentsLoading ? 'animate-spin' : ''"></i>
                                    </button>
                                </div>

                                <div x-show="detailCommentsLoading" class="py-8 text-center text-surface-400">
                                    <i data-lucide="loader-2" class="w-6 h-6 mx-auto animate-spin mb-2"></i>
                                    <p class="text-sm">Memuat komentar...</p>
                                </div>

                                <div x-show="!detailCommentsLoading && detailComments?.length > 0" class="space-y-4">
                                     <template x-for="comment in detailComments?.slice(0, 3)" :key="comment.id">
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="w-8 h-8 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center overflow-hidden ring-2 ring-white dark:ring-surface-900">
                                                    <img 
                                                        x-show="comment.user_avatar"
                                                        :src="comment.user_avatar" 
                                                        class="w-full h-full object-cover"
                                                    >
                                                    <span 
                                                        x-show="!comment.user_avatar"
                                                        class="text-xs font-bold text-surface-500"
                                                        x-text="comment.user_name?.charAt(0).toUpperCase()"
                                                    ></span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm font-semibold text-surface-900 dark:text-white" x-text="comment.user_name"></span>
                                                        <span x-show="comment.status === 'spam'" class="px-1.5 py-0.5 text-[10px] uppercase font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-md">Spam</span>
                                                    </div>
                                                    <span class="text-xs text-surface-400" x-text="comment.time_ago"></span>
                                                </div>
                                                <p class="text-sm text-surface-600 dark:text-surface-300 leading-relaxed" x-text="comment.comment_text"></p>
                                            </div>
                                        </div>
                                     </template>

                                     <button 
                                        x-show="detailComments?.length > 3"
                                        @click="openStatisticsModal(selectedArticle.id); closeDetailModal()"
                                        class="w-full py-3 text-sm font-medium text-theme-600 dark:text-theme-400 hover:text-theme-700 dark:hover:text-theme-300 hover:bg-theme-50 dark:hover:bg-theme-900/20 rounded-xl transition-colors dashed border border-theme-200 dark:border-theme-800"
                                    >
                                        Lihat semua komentar
                                     </button>
                                </div>

                                <div x-show="!detailCommentsLoading && (!detailComments || detailComments.length === 0)" class="text-center py-8 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-dashed border-surface-200 dark:border-surface-700">
                                    <p class="text-sm text-surface-500 font-medium">Belum ada komentar pada artikel ini</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex-shrink-0 p-6 border-t border-surface-100 dark:border-surface-800 bg-surface-50/50 dark:bg-surface-900/50 backdrop-blur-sm flex items-center gap-4">
                        <button 
                            @click="closeDetailModal()"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700 rounded-xl transition-all shadow-sm"
                        >
                            Tutup
                        </button>
                        <button 
                            @click="openEditModal(selectedArticle); closeDetailModal()"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-theme-600 hover:bg-theme-700 rounded-xl transition-all shadow-md shadow-theme-500/20 flex items-center justify-center gap-2"
                        >
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Edit Artikel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
