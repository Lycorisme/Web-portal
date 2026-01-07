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
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="closeStatisticsModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showStatisticsModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl max-h-[90vh] flex flex-col"
                @click.stop
            >
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between bg-gradient-to-r from-theme-500 to-theme-600">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white" x-text="statisticsData?.article_title || 'Statistik Artikel'"></h3>
                            <p class="text-sm text-white/80">Detail interaksi artikel</p>
                        </div>
                    </div>
                    <button 
                        @click="closeStatisticsModal()" 
                        class="p-2 rounded-xl bg-white/20 hover:bg-white/30 transition-colors"
                    >
                        <i data-lucide="x" class="w-5 h-5 text-white"></i>
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    {{-- Loading State --}}
                    <div x-show="statisticsLoading" class="flex items-center justify-center py-12">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 border-4 border-theme-500 border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-sm text-surface-500">Memuat statistik...</span>
                        </div>
                    </div>

                    {{-- Statistics Cards --}}
                    <div x-show="!statisticsLoading && statisticsData" class="space-y-6">
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Views --}}
                            <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                    <span class="text-sm font-medium opacity-90">Views</span>
                                </div>
                                <p class="text-2xl font-bold" x-text="statisticsData?.statistics?.views || 0"></p>
                            </div>

                            {{-- Likes --}}
                            <div class="p-4 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="heart" class="w-5 h-5"></i>
                                    <span class="text-sm font-medium opacity-90">Likes</span>
                                </div>
                                <p class="text-2xl font-bold" x-text="statisticsData?.statistics?.likes || 0"></p>
                            </div>

                            {{-- Comments --}}
                            <div class="p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                                    <span class="text-sm font-medium opacity-90">Komentar</span>
                                </div>
                                <p class="text-2xl font-bold" x-text="statisticsData?.statistics?.comments || 0"></p>
                            </div>

                            {{-- Spam --}}
                            <div class="p-4 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                                    <span class="text-sm font-medium opacity-90">Spam</span>
                                </div>
                                <p class="text-2xl font-bold" x-text="statisticsData?.statistics?.spam_comments || 0"></p>
                            </div>
                        </div>

                        {{-- Recent Likes Section --}}
                        <div class="bg-surface-50 dark:bg-surface-800 rounded-xl p-4">
                            <h4 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                                <i data-lucide="heart" class="w-4 h-4 text-rose-500"></i>
                                Like Terbaru
                            </h4>
                            <div x-show="statisticsData?.recent_likes?.length > 0" class="space-y-2">
                                <template x-for="like in statisticsData?.recent_likes" :key="like.id">
                                    <div class="flex items-center gap-3 p-2 bg-white dark:bg-surface-700 rounded-lg">
                                        <div class="w-8 h-8 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center overflow-hidden">
                                            <img 
                                                x-show="like.user_avatar"
                                                :src="like.user_avatar" 
                                                :alt="like.user_name"
                                                class="w-full h-full object-cover"
                                            >
                                            <span 
                                                x-show="!like.user_avatar"
                                                class="text-xs font-bold text-theme-600 dark:text-theme-400"
                                                x-text="like.user_name?.charAt(0).toUpperCase()"
                                            ></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-surface-900 dark:text-white truncate" x-text="like.user_name"></p>
                                            <p class="text-xs text-surface-500" x-text="like.liked_ago"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-show="!statisticsData?.recent_likes?.length" class="text-center py-4">
                                <i data-lucide="heart-off" class="w-8 h-8 mx-auto text-surface-300 dark:text-surface-600 mb-2"></i>
                                <p class="text-sm text-surface-500">Belum ada like</p>
                            </div>
                        </div>

                        {{-- Comments Section --}}
                        <div class="bg-surface-50 dark:bg-surface-800 rounded-xl p-4">
                            <h4 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                                <i data-lucide="message-circle" class="w-4 h-4 text-emerald-500"></i>
                                Daftar Komentar
                                <span class="ml-auto text-xs font-normal text-surface-500" x-text="`(${statisticsData?.comments?.length || 0} komentar)`"></span>
                            </h4>
                            
                            <div x-show="statisticsData?.comments?.length > 0" class="space-y-3 max-h-80 overflow-y-auto">
                                <template x-for="comment in statisticsData?.comments" :key="comment.id">
                                    <div class="comment-item">
                                        {{-- Main Comment --}}
                                        <div class="p-3 bg-white dark:bg-surface-700 rounded-lg border-l-4" :class="comment.is_admin_reply ? 'border-theme-500' : (comment.status === 'spam' ? 'border-amber-500' : 'border-surface-300 dark:border-surface-600')">
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
                                                    <div class="flex items-center gap-2 mt-2">
                                                        <button 
                                                            @click="openReplyForm(comment)"
                                                            class="text-xs text-theme-600 dark:text-theme-400 hover:underline flex items-center gap-1"
                                                        >
                                                            <i data-lucide="reply" class="w-3 h-3"></i>
                                                            Balas
                                                        </button>
                                                        <button 
                                                            x-show="comment.status === 'visible'"
                                                            @click="hideComment(comment.id)"
                                                            class="text-xs text-surface-500 hover:text-amber-600 flex items-center gap-1"
                                                        >
                                                            <i data-lucide="eye-off" class="w-3 h-3"></i>
                                                            Sembunyikan
                                                        </button>
                                                        <button 
                                                            x-show="comment.status === 'hidden'"
                                                            @click="showComment(comment.id)"
                                                            class="text-xs text-emerald-600 hover:underline flex items-center gap-1"
                                                        >
                                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                                            Tampilkan
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

                                        {{-- Replies (Nested) --}}
                                        <template x-if="comment.replies?.length > 0">
                                            <div class="ml-8 mt-2 space-y-2">
                                                <template x-for="reply in comment.replies" :key="reply.id">
                                                    <div class="p-3 bg-surface-100 dark:bg-surface-600 rounded-lg border-l-4" :class="reply.is_admin_reply ? 'border-theme-500' : 'border-surface-300 dark:border-surface-500'">
                                                        <div class="flex items-start gap-3">
                                                            <div class="w-6 h-6 flex-shrink-0 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center overflow-hidden">
                                                                <img 
                                                                    x-show="reply.user_avatar"
                                                                    :src="reply.user_avatar" 
                                                                    :alt="reply.user_name"
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
                                                                <p class="text-xs text-surface-700 dark:text-surface-300 mt-1" x-text="reply.comment_text"></p>
                                                                <div class="flex items-center gap-2 mt-1">
                                                                    <button 
                                                                        @click="deleteComment(reply.id)"
                                                                        class="text-[10px] text-rose-500 hover:text-rose-600 flex items-center gap-0.5"
                                                                    >
                                                                        <i data-lucide="trash-2" class="w-2.5 h-2.5"></i>
                                                                        Hapus
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            
                            <div x-show="!statisticsData?.comments?.length" class="text-center py-6">
                                <i data-lucide="message-circle" class="w-10 h-10 mx-auto text-surface-300 dark:text-surface-600 mb-2"></i>
                                <p class="text-sm text-surface-500">Belum ada komentar</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reply Form (Floating) --}}
                <div 
                    x-show="replyingTo" 
                    x-transition
                    class="px-6 py-4 bg-surface-100 dark:bg-surface-800 border-t border-surface-200 dark:border-surface-700"
                >
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-surface-500 mb-1">
                                Balas ke: <span class="text-theme-600 dark:text-theme-400" x-text="replyingTo?.user_name"></span>
                            </label>
                            <textarea 
                                x-model="replyText"
                                x-ref="replyTextarea"
                                rows="2"
                                class="w-full px-3 py-2 text-sm bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 rounded-lg focus:ring-2 focus:ring-theme-500 focus:border-transparent resize-none"
                                placeholder="Tulis balasan resmi admin..."
                            ></textarea>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <button 
                            @click="cancelReply()"
                            class="px-3 py-1.5 text-xs text-surface-600 dark:text-surface-400 hover:bg-surface-200 dark:hover:bg-surface-700 rounded-lg transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            @click="submitReply()"
                            :disabled="!replyText.trim() || replyLoading"
                            class="px-4 py-1.5 text-xs bg-theme-gradient text-white font-medium rounded-lg hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1"
                        >
                            <i data-lucide="send" class="w-3 h-3" x-show="!replyLoading"></i>
                            <span class="w-3 h-3 border-2 border-white/30 border-t-white rounded-full animate-spin" x-show="replyLoading"></span>
                            <span x-text="replyLoading ? 'Mengirim...' : 'Kirim Balasan'"></span>
                        </button>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center justify-end">
                    <button 
                        @click="closeStatisticsModal()"
                        class="px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
