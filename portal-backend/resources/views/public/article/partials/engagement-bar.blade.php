{{-- Engagement Bar --}}
<div class="mt-4 sm:mt-6 p-4 sm:p-5 bg-gradient-to-r from-slate-900/80 to-slate-800/50 rounded-xl sm:rounded-2xl border border-slate-700/30 flex flex-wrap items-center justify-between gap-3 sm:gap-4">
    <div class="flex items-center gap-3 sm:gap-4">
        {{-- Like Button --}}
        @auth
            <div x-data="{ liked: {{ $hasLiked ? 'true' : 'false' }}, count: {{ $article->likes_count ?? 0 }} }">
                <form action="{{ route('public.article.like', $article->id) }}" method="POST"
                      @submit.prevent="liked = !liked; count = liked ? count + 1 : count - 1; fetch($el.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })">
                    <button type="submit" class="flex items-center gap-2 group">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center transition-all"
                             :class="liked ? 'bg-rose-500/20 text-rose-500' : 'bg-slate-800 text-slate-400 group-hover:bg-rose-500/20 group-hover:text-rose-500'">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" :class="liked ? 'fill-current' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-300 text-sm" x-text="count"></span>
                    </button>
                </form>
            </div>
        @else
            <div class="relative" x-data="{ showLoginToast: false }">
                <button @click="showLoginToast = true; setTimeout(() => showLoginToast = false, 4000)" 
                        class="flex items-center gap-2 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-slate-800 text-slate-400 group-hover:bg-rose-500/20 group-hover:text-rose-500 flex items-center justify-center transition-all shadow-lg shadow-black/20 border border-slate-700/50 group-hover:border-rose-500/50">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <template x-if="!showLoginToast">
                        <span class="text-xs sm:text-sm text-slate-500 hidden xs:inline font-bold group-hover:text-rose-400 transition-colors">Like</span>
                    </template>
                </button>

                {{-- Toast / Tooltip --}}
                <div x-show="showLoginToast" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     style="display: none;"
                     @click.outside="showLoginToast = false"
                     class="absolute bottom-full left-0 mb-3 w-64 bg-slate-800/90 backdrop-blur-xl border border-rose-500/30 text-white text-xs p-4 rounded-2xl shadow-2xl z-[60]">
                     <div class="flex items-start gap-3">
                         <div class="p-1.5 bg-rose-500/20 rounded-lg text-rose-400 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                         </div>
                         <div>
                            <p class="font-bold text-slate-200 mb-1">Akses Terbatas</p>
                            <p class="text-slate-400 leading-relaxed">Silakan <a href="{{ route('login') }}" class="text-emerald-400 font-bold hover:underline decoration-emerald-500/30">Login</a> terlebih dahulu untuk menyukai berita ini.</p>
                        </div>
                     </div>
                     <div class="absolute bottom-[-6px] left-4 w-3 h-3 bg-slate-800/90 border-r border-b border-rose-500/30 rotate-45"></div>
                </div>
            </div>
        @endauth

        {{-- Comments Count --}}
        <a href="#comments" class="flex items-center gap-2 group">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-slate-800 text-slate-400 group-hover:bg-blue-500/20 group-hover:text-blue-400 flex items-center justify-center transition-all">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <span class="font-bold text-slate-300 text-sm">{{ $article->visibleComments->count() }}</span>
        </a>
    </div>

    {{-- Share Button --}}
    <button onclick="navigator.share ? navigator.share({title: '{{ $article->title }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href).then(() => alert('Link berhasil disalin!'))"
            class="flex items-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-slate-800 hover:bg-emerald-500/20 text-slate-400 hover:text-emerald-400 rounded-lg sm:rounded-xl border border-slate-700 hover:border-emerald-500/50 transition-all text-xs sm:text-sm font-semibold">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
        </svg>
        <span class="hidden sm:inline">Bagikan</span>
    </button>
</div>
