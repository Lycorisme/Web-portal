@extends('public.layouts.public')

@section('meta_title', $article->title)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 160))

@section('content')
<div class="w-full max-w-full overflow-x-hidden">
    
    {{-- Reading Progress Bar --}}
    <div x-data="{ progress: 0 }" 
         @scroll.window="progress = Math.min(100, (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100)"
         class="fixed top-0 left-0 right-0 h-1 z-[100]">
        <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400 transition-all duration-150 ease-out"
             :style="'width: ' + progress + '%'"></div>
    </div>

    {{-- Hero Section --}}
    <header class="relative w-full max-w-full overflow-hidden">
        {{-- Floating Back Button --}}
        <button onclick="history.back()" 
                class="absolute top-24 left-4 sm:top-28 sm:left-6 z-20 inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-slate-900/70 backdrop-blur-md rounded-xl border border-white/10 hover:border-emerald-500/50 text-white/80 hover:text-emerald-400 transition-all group shadow-lg">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-xs sm:text-sm font-semibold">Kembali</span>
        </button>

        @if($article->image_url)
            {{-- With Featured Image --}}
            <div class="relative min-h-[50vh] sm:min-h-[60vh] lg:min-h-[70vh] flex items-end">

                {{-- Background --}}
                <div class="absolute inset-0 overflow-hidden">
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/80 to-[#020617]/40"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#020617]/60 to-transparent"></div>
                </div>
                
                {{-- Content --}}
                <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10 lg:py-16">
                    <div class="max-w-3xl space-y-4 sm:space-y-5">
                        {{-- Meta --}}
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <span class="px-3 sm:px-4 py-1 sm:py-1.5 bg-emerald-500 text-white text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-wider shadow-lg shadow-emerald-500/30">
                                {{ $article->categoryRelation?->name ?? 'Umum' }}
                            </span>
                            <span class="text-slate-300 text-xs sm:text-sm">
                                {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        
                        {{-- Title --}}
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight break-words">
                            {{ $article->title }}
                        </h1>
                        
                        {{-- Author --}}
                        @if($article->author)
                            <div class="flex items-center gap-3 sm:gap-4 pt-2">
                                @if($article->author->avatar_url)
                                    <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" 
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover ring-2 ring-white/20 flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0">
                                        {{ substr($article->author->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-white text-sm sm:text-base truncate">{{ $article->author->name }}</p>
                                    <p class="text-xs sm:text-sm text-slate-400">{{ $article->author->role ?? 'Penulis' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- Without Featured Image --}}
            <div class="pt-32 md:pt-40 pb-8">
                <div class="max-w-5xl mx-auto px-4 sm:px-6">
                    <div class="max-w-3xl space-y-4 sm:space-y-5">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <span class="px-3 sm:px-4 py-1 sm:py-1.5 bg-emerald-500/10 text-emerald-400 text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-wider border border-emerald-500/20">
                                {{ $article->categoryRelation?->name ?? 'Umum' }}
                            </span>
                            <span class="text-slate-400 text-xs sm:text-sm">
                                {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight break-words">
                            {{ $article->title }}
                        </h1>
                        @if($article->author)
                            <div class="flex items-center gap-3 sm:gap-4 pt-2">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-white text-sm sm:text-base truncate">{{ $article->author->name }}</p>
                                    <p class="text-xs sm:text-sm text-slate-400">{{ $article->author->role ?? 'Penulis' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </header>

    {{-- Main Content --}}
    <main class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10 lg:py-14">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
            
            {{-- Article Content --}}
            <article class="flex-1 min-w-0 w-full lg:w-auto">
                
                {{-- Article Body --}}
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl lg:rounded-3xl border border-slate-800/50 p-4 sm:p-6 lg:p-8">
                    <div class="prose prose-sm sm:prose-base lg:prose-lg prose-invert max-w-none w-full
                                prose-headings:text-white prose-headings:font-bold prose-headings:break-words
                                prose-p:text-slate-300 prose-p:leading-relaxed prose-p:break-words
                                prose-a:text-emerald-400 prose-a:no-underline hover:prose-a:underline prose-a:break-all
                                prose-strong:text-white
                                prose-blockquote:border-l-4 prose-blockquote:border-emerald-500 prose-blockquote:pl-4 prose-blockquote:sm:pl-6 prose-blockquote:italic prose-blockquote:text-slate-200 prose-blockquote:bg-emerald-500/5 prose-blockquote:py-3 prose-blockquote:rounded-r-xl
                                prose-ul:space-y-1 prose-li:text-slate-300
                                prose-img:rounded-xl prose-img:lg:rounded-2xl prose-img:border prose-img:border-slate-700 prose-img:max-w-full prose-img:h-auto
                                prose-pre:bg-slate-950 prose-pre:border prose-pre:border-slate-800 prose-pre:rounded-xl prose-pre:overflow-x-auto prose-pre:max-w-full prose-pre:text-xs prose-pre:sm:text-sm
                                prose-code:text-emerald-400 prose-code:bg-slate-800 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:text-xs prose-code:sm:text-sm prose-code:before:content-none prose-code:after:content-none prose-code:break-all
                                prose-table:text-sm prose-table:block prose-table:overflow-x-auto prose-table:max-w-full
                                [&_*]:max-w-full [&_img]:max-w-full [&_iframe]:max-w-full [&_video]:max-w-full [&_table]:max-w-full [&_pre]:max-w-full">
                        {!! $article->content !!}
                    </div>
                </div>

                {{-- Tags --}}
                @if($article->tags && $article->tags->count() > 0)
                    <div class="mt-4 sm:mt-6 p-4 sm:p-5 bg-slate-900/30 rounded-xl sm:rounded-2xl border border-slate-800/30">
                        <p class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 sm:mb-4">Tags</p>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" wire:navigate 
                                   class="px-2.5 sm:px-3 py-1 sm:py-1.5 bg-slate-800/50 hover:bg-emerald-500 text-slate-400 hover:text-white text-[10px] sm:text-xs font-semibold rounded-lg border border-slate-700/50 hover:border-emerald-500 transition-all">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

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
            </article>

            {{-- Sidebar --}}
            <aside class="w-full lg:w-80 flex-shrink-0 min-w-0">
                <div class="lg:sticky lg:top-20 space-y-4 sm:space-y-6">
                    
                    {{-- Related Articles --}}
                    <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-slate-800/50 p-4 sm:p-5">
                        <h4 class="text-sm sm:text-base font-bold text-white mb-4 sm:mb-5 flex items-center gap-2">
                            <span class="w-1 h-4 sm:h-5 bg-gradient-to-b from-emerald-400 to-teal-500 rounded-full flex-shrink-0"></span>
                            Artikel Populer
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
        </div>
    </main>
</div>
@endsection
