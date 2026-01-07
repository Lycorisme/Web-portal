{{-- Statistics Modal --}}
<template x-teleport="body">
    <div 
        x-show="showStatisticsModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="statistics-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showStatisticsModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-md"
            @click="closeStatisticsModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
            <div 
                x-show="showStatisticsModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-2xl bg-white dark:bg-surface-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden max-h-[85vh] flex flex-col"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex-shrink-0 px-6 py-5 border-b border-surface-100 dark:border-surface-800 flex items-center justify-between bg-white/80 dark:bg-surface-900/80 backdrop-blur-md sticky top-0 z-10">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-theme-500 to-theme-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-theme-500/20">
                            <i data-lucide="bar-chart-3" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white truncate leading-tight" x-text="statisticsData?.article_title || 'Statistik Artikel'"></h3>
                            <p class="text-xs font-medium text-surface-500 mt-0.5">Analisis keterlibatan pembaca</p>
                        </div>
                    </div>
                    <button 
                        @click="closeStatisticsModal()" 
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-100 hover:bg-surface-200 dark:bg-surface-800 dark:hover:bg-surface-700 text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 transition-colors"
                    >
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <div class="p-6">
                        {{-- Loading State --}}
                        <div x-show="statisticsLoading" class="flex flex-col items-center justify-center py-20">
                            <div class="w-10 h-10 border-2 border-theme-500/30 border-t-theme-500 rounded-full animate-spin mb-4"></div>
                            <span class="text-sm font-medium text-surface-500">Sedang memuat data statistik...</span>
                        </div>

                        {{-- Statistics Content --}}
                        <div x-show="!statisticsLoading && statisticsData" class="space-y-8">
                            
                            {{-- Stats Grid --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                {{-- Views --}}
                                <div class="relative p-5 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800/30 group hover:-translate-y-1 transition-transform duration-300">
                                    <div class="absolute top-4 right-4 p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-surface-900 dark:text-white mt-2" x-text="statisticsData?.statistics?.views || 0"></p>
                                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wide mt-1">Total Views</p>
                                </div>

                                {{-- Likes --}}
                                <div class="relative p-5 bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-800/30 group hover:-translate-y-1 transition-transform duration-300">
                                    <div class="absolute top-4 right-4 p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400">
                                        <i data-lucide="heart" class="w-4 h-4"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-surface-900 dark:text-white mt-2" x-text="statisticsData?.statistics?.likes || 0"></p>
                                    <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wide mt-1">Total Likes</p>
                                </div>

                                {{-- Comments --}}
                                <div class="relative p-5 bg-emerald-50/50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-800/30 group hover:-translate-y-1 transition-transform duration-300">
                                    <div class="absolute top-4 right-4 p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-surface-900 dark:text-white mt-2" x-text="statisticsData?.statistics?.comments || 0"></p>
                                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide mt-1">Komnetar</p>
                                </div>

                                {{-- Spam --}}
                                <div class="relative p-5 bg-amber-50/50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-800/30 group hover:-translate-y-1 transition-transform duration-300">
                                    <div class="absolute top-4 right-4 p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                                        <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-surface-900 dark:text-white mt-2" x-text="statisticsData?.statistics?.spam_comments || 0"></p>
                                    <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wide mt-1">Spam</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                {{-- Recent Likes --}}
                                <div class="lg:col-span-1 space-y-4">
                                    <h4 class="text-sm font-bold text-surface-900 dark:text-white flex items-center gap-2">
                                        <span class="w-1.5 h-4 bg-rose-500 rounded-full"></span>
                                        Like Terbaru
                                    </h4>
                                    
                                    <div class="bg-surface-50 dark:bg-surface-800/50 rounded-2xl p-4 border border-surface-100 dark:border-surface-800">
                                        <div x-show="statisticsData?.recent_likes?.length > 0" class="space-y-3">
                                            <template x-for="like in statisticsData?.recent_likes?.slice(0, 5)" :key="like.id">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-white dark:bg-surface-800 p-0.5 ring-1 ring-surface-200 dark:ring-surface-700">
                                                        <div class="w-full h-full rounded-full bg-gradient-to-br from-rose-400 to-rose-500 flex items-center justify-center overflow-hidden">
                                                            <img 
                                                                x-show="like.user_avatar"
                                                                :src="like.user_avatar" 
                                                                class="w-full h-full object-cover"
                                                            >
                                                            <span 
                                                                x-show="!like.user_avatar"
                                                                class="text-[10px] font-bold text-white"
                                                                x-text="like.user_name?.charAt(0).toUpperCase()"
                                                            ></span>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-surface-900 dark:text-white truncate" x-text="like.user_name"></p>
                                                        <p class="text-xs text-surface-400" x-text="like.liked_ago"></p>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="statisticsData?.recent_likes?.length > 5" class="pt-2 text-center text-xs text-surface-500">
                                                +<span x-text="statisticsData.recent_likes.length - 5"></span> lainnya
                                            </div>
                                        </div>
                                        
                                        <div x-show="!statisticsData?.recent_likes?.length" class="text-center py-8">
                                            <div class="w-10 h-10 mx-auto bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-2">
                                                <i data-lucide="heart-off" class="w-5 h-5 text-surface-400"></i>
                                            </div>
                                            <p class="text-xs font-medium text-surface-500">Belum ada like</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Comments Feed --}}
                                <div class="lg:col-span-2 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-surface-900 dark:text-white flex items-center gap-2">
                                            <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                                            Diskusi
                                            <span class="px-2 py-0.5 rounded-full bg-surface-100 dark:bg-surface-800 text-xs text-surface-600 dark:text-surface-400" x-text="statisticsData?.comments?.length || 0"></span>
                                        </h4>
                                    </div>

                                    <div class="bg-surface-50 dark:bg-surface-800/50 rounded-2xl border border-surface-100 dark:border-surface-800 overflow-hidden">
                                        <div x-show="statisticsData?.comments?.length > 0" class="divide-y divide-surface-100 dark:divide-surface-800 overflow-y-auto max-h-[400px]">
                                            <template x-for="comment in statisticsData?.comments" :key="comment.id">
                                                <div class="p-4 hover:bg-white dark:hover:bg-surface-800/50 transition-colors">
                                                    {{-- Comment Item --}}
                                                    <div class="flex gap-4">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-br from-surface-200 to-surface-300 dark:from-surface-700 dark:to-surface-600 object-cover ring-2 ring-white dark:ring-surface-800">
                                                                <div class="w-full h-full rounded-full overflow-hidden flex items-center justify-center bg-surface-100 dark:bg-surface-800" :class="{ 'bg-theme-500': comment.is_admin_reply }">
                                                                    <img 
                                                                        x-show="comment.user_avatar"
                                                                        :src="comment.user_avatar" 
                                                                        class="w-full h-full object-cover"
                                                                    >
                                                                    <span 
                                                                        x-show="!comment.user_avatar"
                                                                        class="text-xs font-bold"
                                                                        :class="comment.is_admin_reply ? 'text-white' : 'text-surface-500 dark:text-surface-400'"
                                                                        x-text="comment.user_name?.charAt(0).toUpperCase()"
                                                                    ></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between mb-1.5">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-sm font-bold text-surface-900 dark:text-white" x-text="comment.user_name"></span>
                                                                    <span x-show="comment.is_admin_reply" class="px-1.5 py-0.5 text-[10px] font-bold bg-theme-500 text-white rounded-md shadow-sm shadow-theme-500/20">Admin</span>
                                                                    <span x-show="comment.status === 'spam'" class="px-1.5 py-0.5 text-[10px] font-bold bg-amber-500 text-white rounded-md">Spam</span>
                                                                </div>
                                                                <span class="text-xs text-surface-400 font-medium" x-text="comment.time_ago"></span>
                                                            </div>
                                                            
                                                            <div class="relative">
                                                                <p class="text-sm text-surface-600 dark:text-surface-300 leading-relaxed" 
                                                                   :class="{ 'opacity-50 italic': comment.status === 'hidden' }"
                                                                   x-text="comment.status === 'hidden' ? '(Komentar disembunyikan)' : comment.comment_text"></p>
                                                            </div>

                                                            {{-- Action Buttons --}}
                                                            <div class="flex items-center gap-4 mt-3">
                                                                <button 
                                                                    type="button"
                                                                    @click="openReplyForm(comment)"
                                                                    class="text-xs font-medium text-surface-500 hover:text-theme-600 dark:hover:text-theme-400 flex items-center gap-1.5 transition-colors"
                                                                >
                                                                    <i data-lucide="reply" class="w-3.5 h-3.5"></i> Balas
                                                                </button>
                                                                
                                                                <div class="w-px h-3 bg-surface-200 dark:bg-surface-700"></div>
                                                                
                                                                <button 
                                                                    type="button"
                                                                    x-show="comment.status === 'visible'"
                                                                    @click="hideComment(comment.id)"
                                                                    class="text-xs font-medium text-surface-500 hover:text-amber-600 flex items-center gap-1.5 transition-colors"
                                                                >
                                                                    <i data-lucide="eye-off" class="w-3.5 h-3.5"></i> Hide
                                                                </button>
                                                                <button 
                                                                    type="button"
                                                                    x-show="comment.status === 'hidden'"
                                                                    @click="showComment(comment.id)"
                                                                    class="text-xs font-medium text-surface-500 hover:text-emerald-600 flex items-center gap-1.5 transition-colors"
                                                                >
                                                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Show
                                                                </button>
                                                                
                                                                <div class="w-px h-3 bg-surface-200 dark:bg-surface-700"></div>

                                                                <button 
                                                                    type="button"
                                                                    @click="deleteComment(comment.id)"
                                                                    class="text-xs font-medium text-surface-500 hover:text-rose-600 flex items-center gap-1.5 transition-colors"
                                                                >
                                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                                                </button>
                                                            </div>

                                                            {{-- Replies --}}
                                                            <template x-if="comment.replies?.length > 0">
                                                                <div class="mt-4 space-y-3 pl-4 border-l-2 border-surface-100 dark:border-surface-800">
                                                                    <template x-for="reply in comment.replies" :key="reply.id">
                                                                        <div class="flex gap-3 relative">
                                                                            {{-- Connector Curve --}}
                                                                            <div class="absolute -left-[18px] top-3 w-3 h-px bg-surface-200 dark:bg-surface-700"></div>
                                                                            
                                                                            <div class="w-8 h-8 rounded-full bg-surface-100 dark:bg-surface-800 flex-shrink-0 flex items-center justify-center ring-1 ring-surface-200 dark:ring-surface-700"
                                                                                 :class="{ 'bg-theme-100 dark:bg-theme-900/20': reply.is_admin_reply }">
                                                                                <img 
                                                                                    x-show="reply.user_avatar"
                                                                                    :src="reply.user_avatar" 
                                                                                    class="w-full h-full object-cover rounded-full"
                                                                                >
                                                                                <span 
                                                                                    x-show="!reply.user_avatar"
                                                                                    class="text-[10px] font-bold"
                                                                                    :class="reply.is_admin_reply ? 'text-theme-600 dark:text-theme-400' : 'text-surface-500'"
                                                                                    x-text="reply.user_name?.charAt(0).toUpperCase()"
                                                                                ></span>
                                                                            </div>
                                                                            
                                                                            <div class="flex-1">
                                                                                <div class="bg-surface-50 dark:bg-surface-900 p-3 rounded-lg rounded-tl-none border border-surface-100 dark:border-surface-800">
                                                                                    <div class="flex items-center justify-between mb-1">
                                                                                        <div class="flex items-center gap-2">
                                                                                            <span class="text-xs font-bold text-surface-900 dark:text-white" x-text="reply.user_name"></span>
                                                                                            <span x-show="reply.is_admin_reply" class="px-1 py-0.5 text-[8px] font-bold bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded">Admin</span>
                                                                                        </div>
                                                                                        <span class="text-[10px] text-surface-400" x-text="reply.time_ago"></span>
                                                                                    </div>
                                                                                    <p class="text-xs text-surface-600 dark:text-surface-300" x-text="reply.comment_text"></p>
                                                                                </div>
                                                                                <button 
                                                                                    @click="deleteComment(reply.id)"
                                                                                    class="mt-1 text-[10px] font-medium text-surface-400 hover:text-rose-500 flex items-center gap-1 ml-auto"
                                                                                >
                                                                                    <i data-lucide="trash-2" class="w-2.5 h-2.5"></i> Hapus Balasan
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <div x-show="!statisticsData?.comments?.length" class="text-center py-12">
                                             <div class="w-12 h-12 mx-auto bg-surface-100 dark:bg-surface-800/50 rounded-2xl flex items-center justify-center mb-3 rotate-3">
                                                <i data-lucide="message-square" class="w-6 h-6 text-surface-400"></i>
                                            </div>
                                            <p class="text-sm font-medium text-surface-900 dark:text-white">Belum ada komentar</p>
                                            <p class="text-xs text-surface-500 mt-1">Jadilah yang pertama berkomentar!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reply Form Section --}}
                <div 
                    x-show="replyingTo" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="translate-y-full opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-full opacity-0"
                    class="flex-shrink-0 border-t border-surface-100 dark:border-surface-800 bg-white dark:bg-surface-900 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20"
                >
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="flex items-center gap-1.5 text-theme-600 dark:text-theme-400 font-medium bg-theme-50 dark:bg-theme-900/20 px-2 py-1 rounded-md">
                                    <i data-lucide="corner-down-right" class="w-3.5 h-3.5"></i>
                                    Membalas
                                </span>
                                <span class="font-bold text-surface-900 dark:text-white" x-text="replyingTo?.user_name"></span>
                            </div>
                            <button @click="cancelReply()" class="text-xs font-medium text-surface-400 hover:text-surface-600 flex items-center gap-1 hover:bg-surface-100 dark:hover:bg-surface-800 px-2 py-1 rounded transition-colors">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i> Batal
                            </button>
                        </div>
                        
                        <div class="relative">
                            <textarea 
                                x-model="replyText"
                                x-ref="replyTextarea"
                                rows="2"
                                class="w-full pl-4 pr-12 py-3 text-sm bg-surface-50 dark:bg-surface-800 border-none rounded-xl focus:ring-2 focus:ring-theme-500 placeholder-surface-400 resize-none transition-all"
                                placeholder="Tulis balasan Anda..."
                            ></textarea>
                            
                            <button 
                                @click="submitReply()"
                                :disabled="!replyText.trim() || replyLoading"
                                class="absolute right-2 bottom-2 p-2 rounded-lg bg-theme-600 hover:bg-theme-700 text-white disabled:opacity-50 disabled:bg-surface-200 dark:disabled:bg-surface-700 disabled:text-surface-400 transition-all shadow-sm"
                            >
                                <span x-show="replyLoading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <i x-show="!replyLoading" data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Simple Footer (only if not replying) --}}
                <div 
                    x-show="!replyingTo" 
                    class="flex-shrink-0 px-6 py-4 bg-surface-50/50 dark:bg-surface-900/50 backdrop-blur border-t border-surface-100 dark:border-surface-800"
                >
                    <button 
                        @click="closeStatisticsModal()"
                        class="w-full px-4 py-2.5 text-sm font-semibold text-surface-600 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 border border-surface-200 dark:border-surface-700 rounded-xl transition-colors shadow-sm"
                    >
                        Tutup Statistik
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
