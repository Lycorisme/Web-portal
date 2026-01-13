{{-- Comments Section - Zero Refresh AJAX --}}
<section id="comments" class="mt-8 sm:mt-10 lg:mt-14"
    x-data="commentsSection({{ $article->id }}, {{ auth()->id() ?? 'null' }}, {{ json_encode($article->visibleComments->map(function($c) {
        return [
            'id' => $c->id,
            'user_id' => $c->user_id,
            'comment_text' => $c->comment_text,
            'user_name' => $c->user->name ?? 'Anonim',
            'user_avatar' => $c->user?->avatar_url,
            'time_ago' => $c->created_at->diffForHumans(),
            'show_replies' => false,
            'replies' => $c->visibleReplies->map(function($r) {
                return [
                    'id' => $r->id,
                    'user_id' => $r->user_id,
                    'comment_text' => $r->comment_text,
                    'user_name' => $r->user->name ?? 'Anonim',
                    'user_avatar' => $r->user?->avatar_url,
                    'time_ago' => $r->created_at->diffForHumans(),
                ];
            })->values()
        ];
    })->values()) }})">
    
    <div class="bg-slate-900/80 backdrop-blur-md rounded-[32px] sm:rounded-[40px] border border-white/5 p-6 sm:p-8 lg:p-10 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400 opacity-30"></div>
        <h3 class="text-lg sm:text-xl font-bold text-white mb-5 sm:mb-6 flex items-center gap-2 sm:gap-3">
            Komentar
            <span class="text-xs sm:text-sm bg-emerald-500/10 text-emerald-400 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full"
                  x-text="totalComments()">
            </span>
        </h3>

        {{-- Comment Form --}}
        <div class="mb-6 sm:mb-8">
            @auth
                <form @submit.prevent="submitComment()">
                    <div class="relative">
                        <textarea x-model="newComment" 
                                  rows="3" 
                                  class="w-full p-3 sm:p-4 bg-slate-950 border rounded-lg sm:rounded-xl text-white text-sm placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none resize-none transition-all"
                                  :class="commentError ? 'border-red-500' : 'border-slate-800'"
                                  placeholder="Tulis komentar Anda..."
                                  :disabled="isSubmitting"></textarea>
                        
                        {{-- Loading overlay --}}
                        <div x-show="isSubmitting" 
                             class="absolute inset-0 bg-slate-950/50 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Error message --}}
                    <p x-show="commentError" x-text="commentError" 
                       class="mt-2 text-red-400 text-xs"
                       x-transition></p>
                    
                    {{-- Success message --}}
                    <div x-show="successMessage" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="mt-2 flex items-center gap-2 text-emerald-400 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="successMessage"></span>
                    </div>
                    
                    <div class="flex justify-end mt-3">
                        <button type="submit" 
                                :disabled="isSubmitting || !newComment.trim()"
                                class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <span x-show="!isSubmitting">Kirim Komentar</span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim...
                            </span>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 sm:p-6 bg-slate-950/50 border border-dashed border-slate-700 rounded-lg sm:rounded-xl text-center">
                    <p class="text-slate-400 mb-3 sm:mb-4 text-sm">Masuk untuk ikut berdiskusi</p>
                    <a href="{{ route('login') }}" class="inline-block px-5 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl shadow-lg shadow-emerald-500/20 hover:from-emerald-500 hover:to-teal-400 transition-all">
                        Login
                    </a>
                </div>
            @endauth
        </div>

        {{-- Comments List --}}
        <div class="space-y-4 sm:space-y-6">
            {{-- Existing Comments --}}
            <template x-for="(comment, index) in comments" :key="comment.id">
                <div class="flex gap-2.5 sm:gap-3 group/item"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    {{-- Avatar --}}
                    <template x-if="comment.user_avatar">
                        <img :src="comment.user_avatar" :alt="comment.user_name" class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl object-cover flex-shrink-0">
                    </template>
                    <template x-if="!comment.user_avatar">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0"
                             x-text="comment.user_name.charAt(0).toUpperCase()">
                        </div>
                    </template>
                    
                    <div class="flex-1 min-w-0">
                        {{-- Comment Content --}}
                        <div class="relative bg-slate-950/50 p-3 sm:p-4 rounded-lg sm:rounded-xl border border-slate-800/50 group/card">
                            <div class="flex flex-wrap items-center justify-between gap-1 sm:gap-2 mb-1.5 sm:mb-2 pr-6">
                                <span class="font-semibold text-white text-xs sm:text-sm truncate" x-text="comment.user_name"></span>
                                <span class="text-[10px] sm:text-xs text-slate-500 flex-shrink-0" x-text="comment.time_ago"></span>
                            </div>
                            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed break-words whitespace-pre-line" x-text="comment.comment_text"></p>

                            {{-- Kebab Menu --}}
                            <div class="absolute top-2 right-2" x-data="{ open: false }">
                                <button @click="open = !open" @click.outside="open = false"
                                        class="p-1 text-slate-500 hover:text-white rounded-lg hover:bg-slate-800 transition-colors opacity-0 group-hover/card:opacity-100 focus:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                    class="absolute right-0 mt-1 w-32 bg-slate-900 border border-slate-700 rounded-xl shadow-xl z-20 overflow-hidden text-xs py-1">
                                    
                                    <button @click="open = false; copyToClipboard(comment.comment_text)" 
                                            class="w-full text-left px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        Salin
                                    </button>

                                    <template x-if="currentUserId === comment.user_id">
                                        <div>
                                            <button @click="open = false; openEditModal(comment, null)"
                                                    class="w-full text-left px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </button>
                                            <button @click="open = false; deleteComment(comment.id, null)"
                                                    class="w-full text-left px-3 py-2 text-red-400 hover:bg-rose-500/10 hover:text-red-300 flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Action Bar (Reply, Show Replies) --}}
                        <div class="flex items-center gap-4 mt-2 pl-1">
                            @auth
                            <div x-data="{ showReply: false, replyText: '', isReplying: false, replyError: '' }">
                                <button @click="showReply = !showReply" 
                                        class="text-[10px] sm:text-xs font-bold text-emerald-500 hover:text-emerald-400 uppercase transition-colors">
                                    <span x-text="showReply ? 'Batal' : 'Balas'"></span>
                                </button>
                                
                                {{-- Reply Form --}}
                                <div x-show="showReply" 
                                     x-transition
                                     style="display: none;"
                                     class="mt-2 sm:mt-3 mb-3">
                                    <form @submit.prevent="
                                        if (!replyText.trim()) { replyError = 'Balasan tidak boleh kosong'; return; }
                                        isReplying = true;
                                        replyError = '';
                                        fetch(`{{ url('/p/comment') }}/${comment.id}/reply`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({ comment_text: replyText })
                                        })
                                        .then(r => r.json())
                                        .then(data => {
                                            isReplying = false;
                                            if (data.success) {
                                                if (!comment.replies) comment.replies = [];
                                                comment.replies.push({
                                                    id: data.reply.id,
                                                    user_id: {{ auth()->id() ?? 'null' }},
                                                    comment_text: data.reply.text,
                                                    user_name: data.reply.user.name,
                                                    user_avatar: data.reply.user.avatar,
                                                    time_ago: 'Baru saja'
                                                });
                                                replyText = '';
                                                showReply = false;
                                                comment.show_replies = true; // Auto show replies
                                            } else {
                                                replyError = data.message || 'Gagal mengirim balasan';
                                            }
                                        })
                                        .catch(() => {
                                            isReplying = false;
                                            replyError = 'Terjadi kesalahan. Silakan coba lagi.';
                                        });
                                    " class="flex flex-col sm:flex-row gap-2">
                                        <div class="flex-1 min-w-0 relative">
                                            <input type="text" x-model="replyText" placeholder="Balas..." :disabled="isReplying" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:border-emerald-500 outline-none">
                                        </div>
                                        <button type="submit" :disabled="isReplying" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition-all disabled:opacity-50">Kirim</button>
                                    </form>
                                    <p x-show="replyError" x-text="replyError" class="mt-1 text-red-400 text-[10px]"></p>
                                </div>
                            </div>
                            @endauth

                            {{-- Toggle Replies Button --}}
                            <template x-if="comment.replies && comment.replies.length > 0">
                                <button @click="comment.show_replies = !comment.show_replies" 
                                        class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-slate-500 hover:text-slate-300 transition-colors">
                                    <div class="w-4 h-full flex items-center justify-center">
                                        <svg class="w-3 h-3 transition-transform duration-200" 
                                             :class="comment.show_replies ? 'rotate-90' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                    <span x-text="comment.show_replies ? 'Sembunyikan Balasan' : `Lihat ${comment.replies.length} Balasan`"></span>
                                </button>
                            </template>
                        </div>
                        
                        {{-- Replies List --}}
                        <div x-show="comment.show_replies && comment.replies && comment.replies.length > 0"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-3 sm:mt-4 pl-3 sm:pl-4 border-l-2 border-slate-800 space-y-3">
                            
                            <template x-for="(reply, rIndex) in comment.replies" :key="reply.id">
                                <div class="flex gap-2 sm:gap-3 group/item">
                                    {{-- Reply Avatar --}}
                                    <template x-if="reply.user_avatar">
                                        <img :src="reply.user_avatar" :alt="reply.user_name" class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg object-cover flex-shrink-0">
                                    </template>
                                    <template x-if="!reply.user_avatar">
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white text-[9px] sm:text-xs font-bold flex-shrink-0"
                                             x-text="reply.user_name.charAt(0).toUpperCase()">
                                        </div>
                                    </template>
                                    
                                    {{-- Reply Content --}}
                                    <div class="flex-1 min-w-0 bg-slate-900/50 p-2.5 sm:p-3 rounded-lg border border-slate-800/30 group/card relative">
                                        <div class="flex flex-wrap items-center justify-between gap-1 sm:gap-2 mb-1 pr-6">
                                            <span class="font-semibold text-white text-[10px] sm:text-xs truncate" x-text="reply.user_name"></span>
                                            <span class="text-[9px] sm:text-[10px] text-slate-500 flex-shrink-0" x-text="reply.time_ago"></span>
                                        </div>
                                        <p class="text-slate-400 text-[10px] sm:text-xs leading-relaxed break-words whitespace-pre-line" x-text="reply.comment_text"></p>
                                    
                                        {{-- Kebab Menu for Reply --}}
                                        <div class="absolute top-2 right-2" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false"
                                                    class="p-0.5 text-slate-500 hover:text-white rounded hover:bg-slate-800 transition-colors opacity-0 group-hover/card:opacity-100 focus:opacity-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" 
                                                 x-transition
                                                 class="absolute right-0 mt-1 w-28 bg-slate-900 border border-slate-700 rounded-lg shadow-xl z-20 overflow-hidden text-[11px] py-1">
                                                
                                                <button @click="open = false; copyToClipboard(reply.comment_text)" 
                                                        class="w-full text-left px-3 py-1.5 text-slate-300 hover:bg-slate-800 hover:text-white flex items-center gap-2">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                                    Salin
                                                </button>
            
                                                <template x-if="currentUserId === reply.user_id">
                                                    <div>
                                                        <button @click="open = false; openEditModal(reply, comment.id)"
                                                                class="w-full text-left px-3 py-1.5 text-slate-300 hover:bg-slate-800 hover:text-white flex items-center gap-2">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                            Edit
                                                        </button>
                                                        <button @click="open = false; deleteComment(reply.id, comment.id)"
                                                                class="w-full text-left px-3 py-1.5 text-red-400 hover:bg-rose-500/10 hover:text-red-300 flex items-center gap-2">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            
            {{-- Empty State --}}
            <div x-show="comments.length === 0" class="text-center py-8 sm:py-10">
                <div class="w-12 h-12 sm:w-14 sm:h-14 mx-auto mb-3 sm:mb-4 rounded-full bg-slate-800 flex items-center justify-center">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-slate-500 text-sm">Belum ada komentar. Jadilah yang pertama!</p>
            </div>
        </div>
    </div>

    {{-- Include Teleported Modal --}}
    @include('public.article.partials.edit-comment-modal')

</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function commentsSection(articleId, currentUserId, initialComments) {
    return {
        articleId: articleId,
        currentUserId: currentUserId,
        comments: initialComments || [],
        newComment: '',
        isSubmitting: false,
        commentError: '',
        successMessage: '',
        
        // Edit State
        isEditing: false,
        editCommentId: null,
        editParentId: null, 
        editCommentText: '',
        isSubmittingEdit: false,
        editError: '',

        init() {
            // Watch 'isEditing' to toggle body scroll
            this.$watch('isEditing', value => {
                if (value) {
                    document.body.style.overflow = 'hidden'; // Block scroll
                } else {
                    document.body.style.overflow = ''; // Check default? Or 'auto'
                }
            });
        },

        totalComments() {
             return this.comments.reduce((acc, curr) => {
                 return acc + 1 + (curr.replies ? curr.replies.length : 0);
             }, 0);
        },

        showToast(msg, icon = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1e293b',
                color: '#fff',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: icon,
                title: msg
            })
        },
        
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.showToast('Teks disalin ke clipboard');
            });
        },
        
        async submitComment() {
            if (!this.newComment.trim()) {
                this.commentError = 'Komentar tidak boleh kosong';
                return;
            }
            if (this.newComment.trim().length < 3) {
                this.commentError = 'Komentar minimal 3 karakter';
                return;
            }
            this.isSubmitting = true;
            this.commentError = '';
            
            try {
                const response = await fetch(`{{ url('/p/artikel') }}/${this.articleId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ comment_text: this.newComment })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    this.comments.unshift({
                        id: data.comment.id,
                        user_id: this.currentUserId,
                        comment_text: data.comment.text,
                        user_name: data.comment.user.name,
                        user_avatar: data.comment.user.avatar,
                        time_ago: 'Baru saja',
                        show_replies: false,
                        replies: []
                    });
                    this.newComment = '';
                    this.showToast('Komentar berhasil dikirim!', 'success');
                } else {
                    this.commentError = data.message || 'Gagal mengirim komentar.';
                }
            } catch (error) {
                this.commentError = 'Terjadi kesalahan jaringan.';
            } finally {
                this.isSubmitting = false;
            }
        },

        deleteComment(id, parentId) {
            Swal.fire({
                title: 'Hapus Komentar?',
                text: "Anda tidak dapat mengembalikan komentar yang sudah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#fff',
                iconColor: '#ef4444',
                customClass: {
                    popup: 'border border-slate-700 rounded-2xl shadow-2xl',
                    title: 'text-white',
                    htmlContainer: 'text-slate-300'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('/p/comment') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if(data.success) {
                            if (parentId) {
                                const parent = this.comments.find(c => c.id === parentId);
                                if(parent) {
                                    parent.replies = parent.replies.filter(r => r.id !== id);
                                }
                            } else {
                                this.comments = this.comments.filter(c => c.id !== id);
                            }
                            this.showToast('Komentar berhasil dihapus');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal menghapus komentar',
                                background: '#0f172a',
                                color: '#fff'
                            });
                        }
                    });
                }
            })
        },

        openEditModal(comment, parentId) {
            this.editCommentId = comment.id;
            this.editCommentText = comment.comment_text;
            this.editParentId = parentId;
            this.editError = '';
            this.isEditing = true;
        },

        closeEditModal() {
            this.isEditing = false;
            this.editCommentId = null;
            this.editCommentText = '';
            this.editParentId = null;
        },

        async submitEdit() {
            if(!this.editCommentText.trim()) {
                this.editError = 'Komentar tidak boleh kosong';
                return;
            }
            
            this.isSubmittingEdit = true;
            this.editError = '';

            try {
                const response = await fetch(`{{ url('/p/comment') }}/${this.editCommentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ comment_text: this.editCommentText })
                });

                const data = await response.json();

                if(response.ok && data.success) {
                    if (this.editParentId) {
                        const parent = this.comments.find(c => c.id === this.editParentId);
                        if(parent) {
                            const reply = parent.replies.find(r => r.id === this.editCommentId);
                            if(reply) reply.comment_text = data.comment.text;
                        }
                    } else {
                        const comment = this.comments.find(c => c.id === this.editCommentId);
                        if(comment) comment.comment_text = data.comment.text;
                    }
                    this.closeEditModal();
                    this.showToast('Komentar berhasil diperbarui');
                } else {
                    this.editError = data.message || 'Gagal update komentar';
                }
            } catch (e) {
                this.editError = 'Terjadi kesalahan jaringan';
            } finally {
                this.isSubmittingEdit = false;
            }
        }
    }
}
</script>
@endpush
