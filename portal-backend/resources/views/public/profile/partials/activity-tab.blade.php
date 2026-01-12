{{-- Activity Tab Content --}}

{{-- Liked Articles --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group">
    <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500/5 rounded-full blur-3xl group-hover:bg-pink-500/10 transition-colors duration-700 pointer-events-none"></div>

    <div class="flex items-center justify-between mb-8 relative z-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500/20 to-rose-500/20 flex items-center justify-center shadow-lg shadow-pink-500/10 border border-pink-500/20">
                <i class="fas fa-heart text-pink-400 text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-display font-bold text-white">Berita Disukai</h3>
                <p class="text-xs text-slate-400 mt-1">Koleksi artikel favorit Anda</p>
            </div>
        </div>
        <span class="px-3 py-1 bg-slate-800 rounded-lg text-xs font-bold text-slate-300 border border-slate-700 shadow-inner">
            {{ $likedArticles->total() }} Item
        </span>
    </div>
    
    @if($likedArticles->count() > 0)
        <div class="grid gap-4 relative z-10">
            @foreach($likedArticles as $like)
                @if($like->article)
                <a href="{{ route('public.article.show', $like->article->slug) }}" wire:navigate 
                   class="flex gap-5 p-4 rounded-2xl bg-slate-950/40 border border-slate-800/80 hover:bg-slate-800/50 hover:border-pink-500/30 transition-all duration-300 group/item hover:shadow-lg hover:shadow-pink-500/5 hover:-translate-y-0.5">
                    
                    <div class="w-24 h-24 rounded-xl overflow-hidden shrink-0 bg-slate-900 border border-slate-800 shadow-md relative">
                        @if($like->article->image_url)
                            <img src="{{ $like->article->image_url }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-700">
                                <i class="fas fa-image text-2xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/10 group-hover/item:bg-transparent transition-colors"></div>
                    </div>
                    
                    <div class="flex-1 min-w-0 py-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-bold text-emerald-400 uppercase tracking-wider">
                                    {{ $like->article->categoryRelation?->name ?? 'Umum' }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-medium">
                                    <i class="fas fa-calendar-alt mr-1"></i> {{ $like->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <h4 class="font-bold text-white text-lg group-hover/item:text-pink-400 transition-colors line-clamp-2 leading-tight font-display">
                                {{ $like->article->title }}
                            </h4>
                        </div>
                        
                        <div class="flex items-center justify-end mt-2">
                            <span class="text-xs font-bold text-slate-500 group-hover/item:text-pink-400 flex items-center gap-1.5 transition-colors">
                                Baca Artikel <i class="fas fa-arrow-right text-[10px] transform group-hover/item:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
        
        @if($likedArticles->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $likedArticles->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-16 px-6 bg-slate-950/30 rounded-2xl border border-slate-800/50 border-dashed relative z-10">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-slate-900 flex items-center justify-center shadow-inner">
                <i class="fas fa-heart-broken text-3xl text-slate-700"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-300 mb-2">Belum Ada Artikel Disukai</h4>
            <p class="text-slate-500 text-sm max-w-xs mx-auto mb-6">Jelajahi berita terbaru dan berikan tanda hati pada artikel yang menarik bagi Anda.</p>
            <a href="{{ route('public.articles') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all border border-slate-700 hover:border-slate-600">
                Jelajahi Berita
            </a>
        </div>
    @endif
</div>

{{-- User Comments --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group mt-8">
    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl group-hover:bg-blue-500/10 transition-colors duration-700 pointer-events-none"></div>

    <div class="flex items-center justify-between mb-8 relative z-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center shadow-lg shadow-blue-500/10 border border-blue-500/20">
                <i class="fas fa-comments text-blue-400 text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-display font-bold text-white">Riwayat Komentar</h3>
                <p class="text-xs text-slate-400 mt-1">Diskusi yang Anda ikuti</p>
            </div>
        </div>
        <span class="px-3 py-1 bg-slate-800 rounded-lg text-xs font-bold text-slate-300 border border-slate-700 shadow-inner">
            {{ $userComments->total() }} Item
        </span>
    </div>
    
    @if($userComments->count() > 0)
        <div class="grid gap-4 relative z-10">
            @foreach($userComments as $comment)
                <div class="p-5 rounded-2xl bg-slate-950/40 border border-slate-800/80 hover:border-blue-500/30 hover:bg-slate-900/60 transition-all duration-300 group/comment">
                    <div class="flex justify-between items-start gap-4 mb-3">
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Mengomentari:</span>
                            @if($comment->article)
                                <a href="{{ route('public.article.show', $comment->article->slug) }}" wire:navigate class="font-bold text-base text-white hover:text-blue-400 transition-colors line-clamp-1 font-display">
                                    {{ $comment->article->title }}
                                </a>
                            @else
                                <span class="font-bold text-slate-500 italic">[Artikel telah dihapus]</span>
                            @endif
                        </div>
                        
                        <div class="shrink-0">
                            @if($comment->status === 'visible') 
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Tampil
                                </span>
                            @elseif($comment->status === 'spam') 
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-bold uppercase tracking-wider">
                                    <i class="fas fa-ban"></i> Spam
                                </span>
                            @else 
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">
                                    <i class="fas fa-clock"></i> Pending
                                </span> 
                            @endif
                        </div>
                    </div>
                    
                    <div class="relative pl-4 mb-3 border-l-2 border-slate-700/50">
                        <p class="text-slate-300 text-sm leading-relaxed italic line-clamp-3 group-hover/comment:text-white transition-colors">
                            "{{ $comment->comment_text }}"
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-white/5">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                             <i class="far fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                        </span>
                        
                        @if($comment->is_admin_reply)
                            <span class="text-[10px] font-bold text-emerald-400 flex items-center gap-1.5 bg-emerald-500/5 px-2 py-1 rounded-md border border-emerald-500/10">
                                <i class="fas fa-check-circle"></i> Dibalas Admin
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($userComments->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $userComments->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-16 px-6 bg-slate-950/30 rounded-2xl border border-slate-800/50 border-dashed relative z-10">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-slate-900 flex items-center justify-center shadow-inner">
                <i class="fas fa-comment-slash text-3xl text-slate-700"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-300 mb-2">Belum Ada Komentar</h4>
            <p class="text-slate-500 text-sm max-w-xs mx-auto">Suara Anda penting. Bergabunglah dalam diskusi di artikel yang Anda baca.</p>
        </div>
    @endif
</div>
