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

                        {{-- Comments Section --}}
                        <div class="border-t border-surface-200 dark:border-surface-700 pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-surface-900 dark:text-white flex items-center gap-2">
                                    <i data-lucide="message-circle" class="w-4 h-4 text-emerald-500"></i>
                                    Komentar
                                    <span class="px-2 py-0.5 text-xs bg-surface-100 dark:bg-surface-700 rounded-full" x-text="detailComments?.length || 0"></span>
                                </h4>
                                <button 
                                    @click="fetchDetailComments(selectedArticle.id)"
                                    class="p-1.5 text-surface-400 hover:text-theme-500 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                                    title="Refresh komentar"
                                >
                                    <i data-lucide="refresh-cw" class="w-4 h-4" :class="detailCommentsLoading ? 'animate-spin' : ''"></i>
                                </button>
                            </div>

                            {{-- Loading State --}}
                            <div x-show="detailCommentsLoading" class="text-center py-4">
                                <div class="w-6 h-6 border-2 border-theme-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                            </div>

                            {{-- Comments List --}}
                            <div x-show="!detailCommentsLoading && detailComments?.length > 0" class="space-y-3 max-h-64 overflow-y-auto">
                                <template x-for="comment in detailComments" :key="comment.id">
                                    <div class="comment-item">
                                        {{-- Main Comment --}}
                                        <div class="p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border-l-4" :class="comment.is_admin_reply ? 'border-theme-500' : (comment.status === 'spam' ? 'border-amber-500' : (comment.status === 'hidden' ? 'border-surface-400' : 'border-emerald-500'))">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 flex-shrink-0 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center overflow-hidden">
                                                    <img 
                                                        x-show="comment.user_avatar"
                                                        :src="comment.user_avatar" 
                                                        :alt="comment.user_name"
                                                        class="w-full h-full object-cover"
                                                    >
                                                    <span 
                                                        x-show="!comment.user_avatar"
                                                        class="text-xs font-bold text-theme-600 dark:text-theme-400"
                                                        x-text="comment.user_name?.charAt(0).toUpperCase()"
                                                    ></span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <span class="text-sm font-semibold text-surface-900 dark:text-white" x-text="comment.user_name"></span>
                                                        <span x-show="comment.is_admin_reply" class="px-1.5 py-0.5 text-xs font-medium bg-theme-500 text-white rounded">Admin</span>
                                                        <span x-show="comment.status === 'spam'" class="px-1.5 py-0.5 text-xs font-medium bg-amber-500 text-white rounded">Spam</span>
                                                        <span x-show="comment.status === 'hidden'" class="px-1.5 py-0.5 text-xs font-medium bg-surface-500 text-white rounded">Tersembunyi</span>
                                                        <span class="text-xs text-surface-400" x-text="comment.time_ago"></span>
                                                    </div>
                                                    <p class="text-sm text-surface-700 dark:text-surface-300 mt-1" x-text="comment.comment_text"></p>
                                                    
                                                    {{-- Action Buttons --}}
                                                    <div class="flex items-center gap-3 mt-2">
                                                        <button 
                                                            @click="openStatisticsModal(selectedArticle.id); closeDetailModal()"
                                                            class="text-xs text-theme-600 dark:text-theme-400 hover:underline flex items-center gap-1"
                                                        >
                                                            <i data-lucide="reply" class="w-3 h-3"></i>
                                                            Balas
                                                        </button>
                                                        <button 
                                                            @click="deleteComment(comment.id)"
                                                            class="text-xs text-rose-500 hover:text-rose-600 flex items-center gap-1"
                                                        >
                                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Replies --}}
                                        <template x-if="comment.replies?.length > 0">
                                            <div class="ml-6 mt-2 space-y-2">
                                                <template x-for="reply in comment.replies" :key="reply.id">
                                                    <div class="p-2.5 bg-surface-100 dark:bg-surface-700 rounded-lg border-l-4" :class="reply.is_admin_reply ? 'border-theme-500' : 'border-surface-300'">
                                                        <div class="flex items-start gap-2">
                                                            <div class="w-6 h-6 flex-shrink-0 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center overflow-hidden">
                                                                <img 
                                                                    x-show="reply.user_avatar"
                                                                    :src="reply.user_avatar" 
                                                                    class="w-full h-full object-cover"
                                                                >
                                                                <span 
                                                                    x-show="!reply.user_avatar"
                                                                    class="text-[10px] font-bold text-theme-600 dark:text-theme-400"
                                                                    x-text="reply.user_name?.charAt(0).toUpperCase()"
                                                                ></span>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <span class="text-xs font-semibold text-surface-900 dark:text-white" x-text="reply.user_name"></span>
                                                                    <span x-show="reply.is_admin_reply" class="px-1 py-0.5 text-[10px] font-medium bg-theme-500 text-white rounded">Admin</span>
                                                                    <span class="text-[10px] text-surface-400" x-text="reply.time_ago"></span>
                                                                </div>
                                                                <p class="text-xs text-surface-700 dark:text-surface-300 mt-0.5" x-text="reply.comment_text"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- Empty State --}}
                            <div x-show="!detailCommentsLoading && (!detailComments || detailComments.length === 0)" class="text-center py-6">
                                <i data-lucide="message-circle" class="w-8 h-8 mx-auto text-surface-300 dark:text-surface-600 mb-2"></i>
                                <p class="text-sm text-surface-500">Belum ada komentar</p>
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
