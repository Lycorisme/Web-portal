{{-- Comments Section --}}
<section id="comments" class="mt-8 sm:mt-10 lg:mt-14">
    <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl lg:rounded-3xl border border-slate-800/50 p-4 sm:p-6 lg:p-8">
        <h3 class="text-lg sm:text-xl font-bold text-white mb-5 sm:mb-6 flex items-center gap-2 sm:gap-3">
            Komentar
            <span class="text-xs sm:text-sm bg-emerald-500/10 text-emerald-400 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full">
                {{ $article->visibleComments->count() }}
            </span>
        </h3>

        {{-- Comment Form --}}
        <div class="mb-6 sm:mb-8">
            @auth
                <form action="{{ route('public.article.comment', $article->id) }}" method="POST">
                    @csrf
                    <textarea name="content" rows="3" 
                              class="w-full p-3 sm:p-4 bg-slate-950 border border-slate-800 rounded-lg sm:rounded-xl text-white text-sm placeholder-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none resize-none transition-all"
                              placeholder="Tulis komentar Anda..."></textarea>
                    <div class="flex justify-end mt-3">
                        <button type="submit" class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 sm:p-6 bg-slate-950/50 border border-dashed border-slate-700 rounded-lg sm:rounded-xl text-center">
                    <p class="text-slate-400 mb-3 sm:mb-4 text-sm">Masuk untuk ikut berdiskusi</p>
                    <a href="{{ route('login') }}" wire:navigate class="inline-block px-5 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl shadow-lg shadow-emerald-500/20 hover:from-emerald-500 hover:to-teal-400 transition-all">
                        Login
                    </a>
                </div>
            @endauth
        </div>

        {{-- Comments List --}}
        <div class="space-y-4 sm:space-y-6">
            @forelse($article->visibleComments as $comment)
                <div class="flex gap-2.5 sm:gap-3">
                    @if($comment->user?->avatar_url)
                        <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl object-cover flex-shrink-0">
                    @else
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0">
                            {{ substr($comment->user->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="bg-slate-950/50 p-3 sm:p-4 rounded-lg sm:rounded-xl border border-slate-800/50">
                            <div class="flex flex-wrap items-center justify-between gap-1 sm:gap-2 mb-1.5 sm:mb-2">
                                <span class="font-semibold text-white text-xs sm:text-sm truncate">{{ $comment->user->name ?? 'Anonim' }}</span>
                                <span class="text-[10px] sm:text-xs text-slate-500 flex-shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed break-words">{{ $comment->content }}</p>
                        </div>
                        
                        @auth
                            <div x-data="{ showReply: false }" class="mt-2">
                                <button @click="showReply = !showReply" class="text-[10px] sm:text-xs font-bold text-emerald-500 hover:text-emerald-400 uppercase">
                                    Balas
                                </button>
                                <div x-show="showReply" x-transition class="mt-2 sm:mt-3" style="display: none;">
                                    <form action="{{ route('public.comment.reply', $comment->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                                        @csrf
                                        <input type="text" name="content" placeholder="Balas komentar..." 
                                               class="flex-1 min-w-0 px-3 sm:px-4 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs sm:text-sm text-white placeholder-slate-600 focus:border-emerald-500 outline-none">
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] sm:text-xs font-bold rounded-lg transition-all flex-shrink-0">
                                            Kirim
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth

                        {{-- Replies --}}
                        @if($comment->visibleReplies && $comment->visibleReplies->count() > 0)
                            <div class="mt-3 sm:mt-4 pl-3 sm:pl-4 border-l-2 border-slate-800 space-y-2 sm:space-y-3">
                                @foreach($comment->visibleReplies as $reply)
                                    <div class="flex gap-2 sm:gap-3">
                                        @if($reply->user?->avatar_url)
                                            <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg object-cover flex-shrink-0">
                                        @else
                                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white text-[9px] sm:text-xs font-bold flex-shrink-0">
                                                {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0 bg-slate-900/50 p-2.5 sm:p-3 rounded-lg border border-slate-800/30">
                                            <div class="flex flex-wrap items-center justify-between gap-1 sm:gap-2 mb-1">
                                                <span class="font-semibold text-white text-[10px] sm:text-xs truncate">{{ $reply->user->name ?? 'Anonim' }}</span>
                                                <span class="text-[9px] sm:text-[10px] text-slate-500 flex-shrink-0">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-400 text-[10px] sm:text-xs leading-relaxed break-words">{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 sm:py-10">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 mx-auto mb-3 sm:mb-4 rounded-full bg-slate-800 flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm">Belum ada komentar. Jadilah yang pertama!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
