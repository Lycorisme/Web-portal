{{-- Activity Tab Content --}}

{{-- Liked Articles --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-pink-500/10 flex items-center justify-center">
            <i class="fas fa-heart text-pink-500"></i>
        </div>
        Berita yang Disukai
        <span class="ml-auto text-sm text-slate-500">{{ $likedArticles->total() }} artikel</span>
    </h3>
    
    @if($likedArticles->count() > 0)
        <div class="space-y-4">
            @foreach($likedArticles as $like)
                @if($like->article)
                <a href="{{ route('public.article.show', $like->article->slug) }}" wire:navigate 
                   class="flex gap-4 p-4 rounded-xl bg-slate-800/30 hover:bg-slate-800/50 border border-slate-700/30 hover:border-pink-500/30 transition-all group">
                    <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 bg-slate-800">
                        @if($like->article->image_url)
                            <img src="{{ $like->article->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-600">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-white group-hover:text-pink-400 transition-colors line-clamp-2 mb-1">
                            {{ $like->article->title }}
                        </h4>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span><i class="fas fa-folder mr-1"></i> {{ $like->article->categoryRelation?->name ?? 'Umum' }}</span>
                            <span><i class="fas fa-clock mr-1"></i> {{ $like->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-heart text-pink-500"></i>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
        
        @if($likedArticles->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $likedArticles->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-800 flex items-center justify-center">
                <i class="fas fa-heart text-2xl text-slate-600"></i>
            </div>
            <p class="text-slate-500 font-semibold">Belum ada artikel yang disukai</p>
            <p class="text-slate-600 text-sm mt-1">Tekan tombol ❤️ pada artikel untuk menyimpannya</p>
        </div>
    @endif
</div>

{{-- User Comments --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
            <i class="fas fa-comments text-blue-500"></i>
        </div>
        Riwayat Komentar
        <span class="ml-auto text-sm text-slate-500">{{ $userComments->total() }} komentar</span>
    </h3>
    
    @if($userComments->count() > 0)
        <div class="space-y-4">
            @foreach($userComments as $comment)
                <div class="p-4 rounded-xl bg-slate-800/30 border border-slate-700/30 hover:border-blue-500/30 transition-all">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex-1">
                            @if($comment->article)
                                <a href="{{ route('public.article.show', $comment->article->slug) }}" wire:navigate class="font-bold text-white hover:text-blue-400 transition-colors">
                                    {{ $comment->article->title }}
                                </a>
                            @else
                                <span class="font-bold text-slate-500">[Artikel Dihapus]</span>
                            @endif
                        </div>
                        <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                            @if($comment->status === 'visible') bg-emerald-500/10 text-emerald-400 border border-emerald-500/30
                            @elseif($comment->status === 'spam') bg-rose-500/10 text-rose-400 border border-rose-500/30
                            @else bg-amber-500/10 text-amber-400 border border-amber-500/30 @endif">
                            {{ $comment->status === 'visible' ? 'Tampil' : ($comment->status === 'spam' ? 'Spam' : 'Pending') }}
                        </span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-3 line-clamp-3">
                        {{ $comment->comment_text }}
                    </p>
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span><i class="fas fa-clock mr-1"></i> {{ $comment->created_at->diffForHumans() }}</span>
                        @if($comment->is_admin_reply)
                            <span class="text-emerald-400"><i class="fas fa-shield-alt mr-1"></i> Balasan Admin</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($userComments->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $userComments->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-800 flex items-center justify-center">
                <i class="fas fa-comments text-2xl text-slate-600"></i>
            </div>
            <p class="text-slate-500 font-semibold">Belum ada komentar</p>
            <p class="text-slate-600 text-sm mt-1">Berikan pendapat Anda pada artikel yang menarik</p>
        </div>
    @endif
</div>
