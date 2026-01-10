{{-- Article Detail Modal for Dashboard --}}
{{-- Use teleport to move modal outside of content wrapper for proper viewport centering --}}
<template x-teleport="body">
    <div 
        x-show="showArticleModal"
        x-cloak
        class="fixed inset-0 z-[9999] overflow-y-auto"
        aria-labelledby="article-modal-title" 
        role="dialog" 
        aria-modal="true"
        style="margin: 0 !important; padding: 0 !important;"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showArticleModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm"
            @click="closeArticleModal()"
        ></div>

        {{-- Modal Container - Centered in viewport --}}
        <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6">
            <div 
                x-show="showArticleModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-2xl bg-white dark:bg-surface-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden max-h-[90vh] flex flex-col"
                @click.stop
            >
                {{-- Content when selectedArticle exists --}}
                <template x-if="selectedArticle">
                    <div class="flex flex-col h-full max-h-[90vh]">
                        
                        {{-- Header with Thumbnail --}}
                        <div class="relative h-48 sm:h-56 flex-shrink-0 group overflow-hidden">
                            {{-- Image Background Placeholder --}}
                            <div 
                                x-show="!selectedArticle.thumbnail"
                                class="absolute inset-0 bg-gradient-to-br from-theme-600 to-theme-800 flex items-center justify-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-white/30"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                            </div>
                            
                            {{-- Actual Thumbnail --}}
                            <img 
                                x-show="selectedArticle.thumbnail"
                                :src="selectedArticle.thumbnail" 
                                :alt="selectedArticle.title" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                x-on:error="$el.style.display = 'none'"
                            >
                            
                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-surface-900/90 via-surface-900/40 to-transparent"></div>
                            
                            {{-- Top Actions --}}
                            <div class="absolute top-4 right-4 z-10">
                                <button 
                                    @click="closeArticleModal()" 
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-black/30 text-white hover:bg-black/50 backdrop-blur-md transition-all border border-white/10"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>

                            {{-- Title & Category Info --}}
                            <div class="absolute bottom-0 left-0 right-0 p-6 pt-12">
                                <div class="flex items-center gap-3 mb-3">
                                    <span 
                                        x-show="selectedArticle.category_name"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold backdrop-blur-md shadow-sm border border-white/10 text-white"
                                        :style="`background-color: ${selectedArticle.category_color || '#6366f1'}CC`"
                                    >
                                        <span x-text="selectedArticle.category_name"></span>
                                    </span>
                                    
                                    <div class="flex items-center gap-2 text-white/80 text-xs font-medium backdrop-blur-md px-2 py-1 rounded-full bg-black/20 border border-white/10">
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <span x-text="selectedArticle.views || 0"></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <h3 class="text-xl sm:text-2xl font-bold text-white leading-tight tracking-tight drop-shadow-sm line-clamp-2" id="article-modal-title" x-text="selectedArticle.title"></h3>
                            </div>
                        </div>

                        {{-- Scrollable Content --}}
                        <div class="p-6 space-y-6 overflow-y-auto flex-grow custom-scrollbar">
                            
                            {{-- Author & Status Bar --}}
                            <div class="flex items-center justify-between py-4 border-b border-surface-100 dark:border-surface-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-theme-400 to-theme-600 flex items-center justify-center text-white font-bold">
                                        <span x-text="(selectedArticle.author_name || 'A').charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-surface-900 dark:text-white" x-text="selectedArticle.author_name || 'Admin'"></p>
                                        <p class="text-xs text-surface-500 font-medium" x-text="selectedArticle.published_at || selectedArticle.created_at"></p>
                                    </div>
                                </div>

                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset"
                                    :class="getStatusColor(selectedArticle.status)"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    <span x-text="getStatusLabel(selectedArticle.status)"></span>
                                </span>
                            </div>

                            {{-- Excerpt --}}
                            <div x-show="selectedArticle.excerpt" class="relative pl-4 border-l-2 border-theme-500">
                                <p class="text-base font-medium text-surface-800 dark:text-surface-200 italic leading-relaxed" x-text="selectedArticle.excerpt"></p>
                            </div>

                            {{-- URL --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold text-surface-400 dark:text-surface-500 uppercase tracking-widest">Public URL</label>
                                    <button 
                                        @click="navigator.clipboard.writeText('/berita/' + selectedArticle.slug)" 
                                        class="text-xs font-medium text-theme-600 dark:text-theme-400 hover:text-theme-700 flex items-center gap-1 transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                        Copy
                                    </button>
                                </div>
                                <div class="p-3 bg-surface-50 dark:bg-surface-800 rounded-lg border border-surface-100 dark:border-surface-700 font-mono text-sm text-surface-600 dark:text-surface-300 break-all select-all">
                                    <span class="text-surface-400 select-none">/berita/</span><span x-text="selectedArticle.slug"></span>
                                </div>
                            </div>

                            {{-- Meta Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                 <div class="p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50">
                                    <p class="text-xs text-surface-400 font-medium uppercase tracking-wider mb-1">Dibuat</p>
                                    <p class="text-sm font-semibold text-surface-900 dark:text-white" x-text="selectedArticle.created_at"></p>
                                </div>
                                <div class="p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50">
                                    <p class="text-xs text-surface-400 font-medium uppercase tracking-wider mb-1">Diperbarui</p>
                                    <p class="text-sm font-semibold text-surface-900 dark:text-white" x-text="selectedArticle.updated_at"></p>
                                </div>
                            </div>

                            {{-- Article Content Preview --}}
                            <div class="pt-4 border-t border-surface-100 dark:border-surface-800">
                                <h4 class="text-md font-bold text-surface-900 dark:text-white mb-4">Ringkasan Konten</h4>
                                <div 
                                    class="text-sm text-surface-700 dark:text-surface-300 leading-relaxed line-clamp-6 prose prose-sm dark:prose-invert max-w-none" 
                                    x-html="selectedArticle.content"
                                ></div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex-shrink-0 p-6 border-t border-surface-100 dark:border-surface-800 bg-surface-50/50 dark:bg-surface-900/50 backdrop-blur-sm flex items-center gap-4">
                            <button 
                                @click="closeArticleModal()"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700 rounded-xl transition-all shadow-sm"
                            >
                                Tutup
                            </button>
                            <a 
                                :href="'/articles/' + selectedArticle.id + '/edit'"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-theme-600 hover:bg-theme-700 rounded-xl transition-all shadow-md shadow-theme-500/20 flex items-center justify-center gap-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                Edit Artikel
                            </a>
                        </div>
                    </div>
                </template>

                {{-- Loading State --}}
                <div x-show="showArticleModal && !selectedArticle" class="flex items-center justify-center py-20">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-theme-500 border-t-transparent"></div>
                </div>
            </div>
        </div>
    </div>
</template>
