{{-- Article Sidebar --}}
<aside class="w-full lg:w-80 flex-shrink-0 min-w-0">
    <div class="lg:sticky lg:top-20 space-y-4 sm:space-y-6">
        
        {{-- Related Articles --}}
        <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-slate-800/50 p-4 sm:p-5">
            <h4 class="text-sm sm:text-base font-bold text-white mb-4 sm:mb-5 flex items-center gap-2">
                <span class="w-1 h-4 sm:h-5 bg-gradient-to-b from-emerald-400 to-teal-500 rounded-full flex-shrink-0"></span>
                Artikel Serupa
            </h4>
            <div class="space-y-3 sm:space-y-4">
                @forelse($relatedArticles as $index => $related)
                    <a href="{{ route('public.article.show', $related->slug) }}" wire:navigate class="group flex gap-2.5 sm:gap-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-800 group-hover:text-emerald-500 transition-colors w-6 sm:w-8 flex-shrink-0">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <h5 class="text-xs sm:text-sm font-semibold text-slate-300 group-hover:text-white line-clamp-2 leading-snug transition-colors break-words">
                                {{ $related->title }}
                            </h5>
                            <p class="text-[9px] sm:text-[10px] text-slate-600 mt-1 uppercase font-bold">
                                {{ $related->likes_count ?? 0 }} Likes
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="text-xs sm:text-sm text-slate-500 text-center py-4">Tidak ada artikel terkait</p>
                @endforelse
            </div>
        </div>

        {{-- Tags Cloud --}}
        @php
            $allTags = \App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->take(15)->get();
        @endphp
        @if($allTags->count() > 0)
            <div class="bg-slate-900/30 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-slate-800/30 p-4 sm:p-5">
                <h4 class="text-sm sm:text-base font-bold text-white mb-4 sm:mb-5 flex items-center gap-2">
                    <span class="p-1 sm:p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 flex-shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </span>
                    Jelajahi Tag
                </h4>
                <div class="flex flex-wrap gap-1.5 sm:gap-2">
                    @foreach($allTags as $tag)
                        <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" wire:navigate 
                           class="px-2 sm:px-3 py-1 sm:py-1.5 bg-slate-800/50 hover:bg-emerald-500 text-slate-400 hover:text-white text-[10px] sm:text-xs font-semibold rounded-lg border border-slate-700/50 hover:border-emerald-500 transition-all">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        
    </div>
</aside>
