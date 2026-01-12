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
    <div x-data="{ 
        showShare: false,
        copied: false,
        articleUrl: window.location.href,
        articleTitle: `{{ addslashes($article->title) }}`,
        copyLink() {
            navigator.clipboard.writeText(this.articleUrl).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        },
        nativeShare() {
            if (navigator.share) {
                navigator.share({
                    title: this.articleTitle,
                    url: this.articleUrl
                });
            }
        }
    }" class="relative">
        <button @click="showShare = !showShare"
                class="flex items-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-slate-800 hover:bg-emerald-500/20 text-slate-400 hover:text-emerald-400 rounded-lg sm:rounded-xl border border-slate-700 hover:border-emerald-500/50 transition-all text-xs sm:text-sm font-semibold">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
            </svg>
            <span class="hidden sm:inline">Bagikan</span>
        </button>

        {{-- Share Modal --}}
        <div x-show="showShare" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             @click.outside="showShare = false"
             class="absolute right-0 bottom-full mb-3 w-72 sm:w-80 bg-slate-900/95 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-2xl z-50 overflow-hidden"
             style="display: none;">
            
            {{-- Header --}}
            <div class="p-4 border-b border-slate-800/50">
                <h4 class="text-white font-bold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Bagikan Artikel
                </h4>
                <p class="text-slate-500 text-xs mt-1 line-clamp-1">{{ $article->title }}</p>
            </div>

            {{-- Share Options --}}
            <div class="p-3 grid grid-cols-4 gap-2">
                {{-- WhatsApp --}}
                <a :href="`https://wa.me/?text=${encodeURIComponent(articleTitle + ' ' + articleUrl)}`" 
                   target="_blank" rel="noopener"
                   class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-slate-800/50 hover:bg-green-500/20 border border-transparent hover:border-green-500/30 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white shadow-lg shadow-green-500/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <span class="text-[10px] text-slate-400 group-hover:text-green-400 font-medium transition-colors">WhatsApp</span>
                </a>

                {{-- Telegram --}}
                <a :href="`https://t.me/share/url?url=${encodeURIComponent(articleUrl)}&text=${encodeURIComponent(articleTitle)}`" 
                   target="_blank" rel="noopener"
                   class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-slate-800/50 hover:bg-blue-500/20 border border-transparent hover:border-blue-500/30 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    </div>
                    <span class="text-[10px] text-slate-400 group-hover:text-blue-400 font-medium transition-colors">Telegram</span>
                </a>

                {{-- Twitter/X --}}
                <a :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(articleTitle)}&url=${encodeURIComponent(articleUrl)}`" 
                   target="_blank" rel="noopener"
                   class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-slate-800/50 hover:bg-slate-600/50 border border-transparent hover:border-slate-500/30 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white shadow-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </div>
                    <span class="text-[10px] text-slate-400 group-hover:text-white font-medium transition-colors">X</span>
                </a>

                {{-- Facebook --}}
                <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(articleUrl)}`" 
                   target="_blank" rel="noopener"
                   class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-slate-800/50 hover:bg-blue-600/20 border border-transparent hover:border-blue-600/30 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-600/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                    <span class="text-[10px] text-slate-400 group-hover:text-blue-400 font-medium transition-colors">Facebook</span>
                </a>
            </div>

            {{-- Copy Link Section --}}
            <div class="px-3 pb-3">
                <div class="flex items-center gap-2 p-2.5 bg-slate-950/50 rounded-xl border border-slate-800/50">
                    <input type="text" :value="articleUrl" readonly 
                           class="flex-1 bg-transparent text-slate-300 text-xs outline-none truncate">
                    <button @click="copyLink()" 
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                            :class="copied ? 'bg-emerald-500 text-white' : 'bg-slate-700 hover:bg-emerald-500/20 text-slate-300 hover:text-emerald-400'">
                        <template x-if="copied">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="!copied">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                        </template>
                        <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                    </button>
                </div>
            </div>

            {{-- Native Share (Mobile) --}}
            <template x-if="!!navigator.share">
                <div class="px-3 pb-3">
                    <button @click="nativeShare()" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Opsi Lainnya
                    </button>
                </div>
            </template>

            {{-- Arrow --}}
            <div class="absolute bottom-[-6px] right-4 w-3 h-3 bg-slate-900/95 border-r border-b border-slate-700/50 rotate-45"></div>
        </div>
    </div>
</div>

