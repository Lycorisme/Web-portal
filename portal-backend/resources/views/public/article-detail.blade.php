@extends('layouts.public-layout')

@section('meta_title', $article->title)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 160))

@section('content')
    <!-- Progress Bar -->
    <div x-data="{ width: '0%' }" 
         @scroll.window="width = ((window.pageYOffset) / (document.documentElement.scrollHeight - window.innerHeight) * 100) + '%'"
         class="fixed top-0 left-0 h-1 z-[60] bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-100" 
         :style="'width: ' + width"></div>

    <!-- WRAPPER UTAMA - Mencegah horizontal overflow -->
    <div class="w-full overflow-x-hidden">

        <main class="max-w-7xl mx-auto px-4 md:px-6 py-8 md:py-12">
            
            <!-- Article Header -->
            <header class="max-w-4xl mb-12">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-400 text-xs font-bold rounded-full border border-emerald-500/20 tracking-wider uppercase">
                        {{ $article->categoryRelation?->name ?? 'Umum' }}
                    </span>
                    <span class="text-slate-600">•</span>
                    <span class="text-sm text-slate-500 font-medium">
                        {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 md:mb-8 tracking-tight break-words">
                    {{ $article->title }}
                </h1>
                @if($article->author)
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-900/50 border border-slate-800 w-fit max-w-full">
                        @if($article->author->avatar_url)
                            <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ substr($article->author->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ $article->author->name }}</p>
                            <p class="text-xs text-slate-500">{{ $article->author->role ?? 'Penulis' }}</p>
                        </div>
                    </div>
                @endif
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 w-full">
                
                <!-- Main Article Content -->
                <article class="lg:col-span-8 space-y-8 min-w-0">
                    <!-- Featured Image -->
                    @if($article->image_url)
                        <div class="relative group overflow-hidden rounded-[24px] md:rounded-[32px] border border-slate-800">
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full aspect-video object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent opacity-60"></div>
                        </div>
                    @endif

                    <!-- Article Body - FIXED PROSE OVERFLOW -->
                    <div class="prose prose-base md:prose-lg prose-invert max-w-none w-full
                                prose-headings:text-white prose-headings:font-extrabold prose-headings:break-words
                                prose-p:text-slate-400 prose-p:leading-relaxed prose-p:break-words
                                prose-a:text-emerald-400 prose-a:no-underline hover:prose-a:underline prose-a:break-all
                                prose-strong:text-white
                                prose-blockquote:border-l-4 prose-blockquote:border-emerald-500 prose-blockquote:pl-6 prose-blockquote:py-2 prose-blockquote:my-8 prose-blockquote:italic prose-blockquote:text-white prose-blockquote:font-medium prose-blockquote:bg-emerald-500/5 prose-blockquote:rounded-r-2xl
                                prose-ul:space-y-2 prose-li:text-slate-400
                                prose-img:rounded-2xl prose-img:border prose-img:border-slate-800 prose-img:max-w-full prose-img:h-auto
                                prose-pre:overflow-x-auto prose-pre:max-w-full
                                prose-code:break-all
                                prose-table:block prose-table:overflow-x-auto prose-table:max-w-full
                                [&_*]:max-w-full [&_iframe]:max-w-full [&_video]:max-w-full [&_embed]:max-w-full">
                        {!! $article->content !!}
                    </div>

                    <!-- Tags - FIXED OVERFLOW -->
                    @if($article->tags && $article->tags->count() > 0)
                        <div class="flex flex-wrap gap-2 py-8 border-y border-slate-800">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" 
                                   class="inline-block px-3 md:px-4 py-1.5 md:py-2 bg-slate-900 border border-slate-800 rounded-xl text-[10px] md:text-xs font-bold text-slate-400 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all whitespace-nowrap">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-4 min-w-0">
                    <div class="sticky top-24 space-y-8">
                        <!-- Popular Articles -->
                        <div class="p-5 md:p-8 rounded-[24px] md:rounded-[32px] bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800">
                            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                                <span class="w-2 h-6 bg-emerald-500 rounded-full flex-shrink-0"></span>
                                <span class="truncate">Paling Populer</span>
                            </h3>
                            <div class="space-y-6">
                                @foreach($relatedArticles as $index => $related)
                                    <a href="{{ route('public.article.show', $related->slug) }}" class="group flex gap-4 min-w-0">
                                        <span class="text-3xl font-black text-slate-800 group-hover:text-emerald-500 transition-colors flex-shrink-0">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm font-bold text-slate-300 group-hover:text-white leading-snug line-clamp-2 break-words">
                                                {{ $related->title }}
                                            </h4>
                                            <p class="text-[10px] text-slate-600 mt-1 uppercase font-bold truncate">
                                                {{ $related->likes_count ?? 0 }} Likes • {{ $related->published_at ? \Carbon\Carbon::parse($related->published_at)->diffForHumans() : '-' }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tags Cloud -->
                        @php
                            $allTags = \App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->get();
                        @endphp
                        @if($allTags->count() > 0)
                            <div class="p-5 md:p-8 rounded-[24px] md:rounded-[32px] bg-slate-900/50 border border-slate-800 backdrop-blur-sm" x-data="{ expanded: false }">
                                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                                    <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </span>
                                    <span class="truncate">Jelajahi Tag</span>
                                </h3>
                                
                                <div class="flex flex-wrap gap-2.5">
                                    @foreach($allTags as $index => $tag)
                                        <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" 
                                           class="inline-block px-3 md:px-4 py-2 md:py-2.5 bg-slate-950 border border-slate-800/60 rounded-2xl text-[10px] md:text-xs font-bold text-slate-400 hover:bg-emerald-600 hover:text-white hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/20 transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap"
                                           @if($index >= 10) x-show="expanded" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" @endif>
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>

                                @if($allTags->count() > 10)
                                    <div class="mt-6 pt-4 border-t border-slate-800/50">
                                        <button @click="expanded = !expanded" 
                                                class="w-full group flex items-center justify-center gap-2 text-xs font-bold text-slate-500 hover:text-white transition-colors py-2 rounded-xl hover:bg-slate-800/50">
                                            <span x-text="expanded ? 'Sembunyikan' : 'Lihat Semua ({{ $allTags->count() - 10 }} lagi)'"></span>
                                            <svg class="w-4 h-4 transition-transform duration-300 flex-shrink-0" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Back to Articles -->
                        <a href="{{ route('public.articles') }}" 
                           class="flex items-center gap-3 p-4 md:p-6 rounded-2xl bg-slate-900/30 border border-slate-800 hover:border-emerald-500/50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 group-hover:bg-emerald-500/20 flex items-center justify-center transition-all flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors truncate">Kembali ke Berita</p>
                                <p class="text-xs text-slate-600 truncate">Lihat semua artikel lainnya</p>
                            </div>
                        </a>
                    </div>
                </aside>
            </div>

            <!-- BOTTOM SECTION -->
            <div class="max-w-4xl mx-auto mt-12 md:mt-16 space-y-8 md:space-y-12 mb-16 md:mb-24">
                <!-- Engagement Section -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 py-8 border-y border-slate-800">
                    <div class="flex items-center gap-6">
                        @auth
                            <div x-data="{ liked: {{ $hasLiked ? 'true' : 'false' }}, count: {{ $article->likes_count ?? 0 }} }">
                                <form action="{{ route('public.article.like', $article->id) }}" method="POST" 
                                      @submit.prevent="liked = !liked; count = liked ? count + 1 : count - 1; fetch($el.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })">
                                    <button type="submit" 
                                            class="flex items-center gap-2 group cursor-pointer">
                                        <div class="p-3 rounded-xl transition-all" 
                                             :class="liked ? 'bg-rose-500/10 text-rose-500' : 'bg-slate-800 text-slate-500 group-hover:bg-rose-500/10 group-hover:text-rose-500'">
                                            <svg class="w-6 h-6" :class="liked ? 'fill-current' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                        <span class="font-bold text-slate-300" x-text="count"></span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-2 group cursor-pointer">
                                <div class="p-3 rounded-xl bg-slate-800 text-slate-500 group-hover:bg-rose-500/10 group-hover:text-rose-500 transition-all flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-500 group-hover:text-rose-500 transition-colors whitespace-nowrap">Login untuk Like</span>
                            </a>
                        @endauth
                    </div>
                    <div class="flex gap-3">
                        <button onclick="navigator.share ? navigator.share({title: '{{ $article->title }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href).then(() => alert('Link disalin!'))"
                                class="p-3 rounded-xl bg-slate-900 border border-slate-800 hover:border-emerald-500 hover:text-emerald-500 transition-all text-slate-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Comments Section -->
                <section class="pt-8" id="comments">
                    <h3 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                        <span>Komentar</span>
                        <span class="text-emerald-500 text-sm bg-emerald-500/10 px-3 py-1 rounded-lg flex-shrink-0">
                            {{ $article->visibleComments->count() }}
                        </span>
                    </h3>

                    <!-- Comment Form -->
                    <div class="mb-12">
                        @auth
                            <div class="space-y-4">
                                <form action="{{ route('public.article.comment', $article->id) }}" method="POST">
                                    @csrf
                                    <textarea name="content" rows="4" 
                                              class="w-full p-4 md:p-6 bg-slate-900 border border-slate-800 rounded-[16px] md:rounded-[24px] text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all placeholder-slate-600 resize-none text-sm md:text-base" 
                                              placeholder="Tulis komentar Anda secara bijak..."></textarea>
                                    <div class="flex justify-end mt-4">
                                        <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all">
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-5 md:p-8 rounded-[16px] md:rounded-[24px] bg-slate-900/50 border border-dashed border-slate-700 flex flex-col items-center text-center">
                                <p class="text-slate-400 font-medium mb-4">Ingin ikut berdiskusi? Masuk ke akun Anda sekarang.</p>
                                <a href="{{ route('login') }}" class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:from-emerald-500 hover:to-teal-400 transition-all">
                                    Login Area
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-8">
                        @forelse($article->visibleComments as $comment)
                            <div class="flex gap-3 md:gap-4 w-full">
                                @if($comment->user?->avatar_url)
                                    <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-10 h-10 md:w-12 md:h-12 rounded-xl object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($comment->user->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0 bg-slate-900/40 p-4 md:p-6 rounded-[16px] md:rounded-[24px] border border-slate-800/50">
                                    <div class="flex flex-wrap justify-between items-center gap-2 mb-2">
                                        <h4 class="font-bold text-white truncate">{{ $comment->user->name ?? 'Anonymous' }}</h4>
                                        <span class="text-xs text-slate-500 flex-shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-400 text-sm leading-relaxed break-words overflow-wrap-anywhere">{{ $comment->content }}</p>
                                    
                                    @auth
                                        <div x-data="{ open: false }" class="mt-4">
                                            <button @click="open = !open" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 uppercase tracking-wide">
                                                BALAS
                                            </button>
                                            <div x-show="open" x-transition class="mt-3" style="display: none;">
                                                <form action="{{ route('public.comment.reply', $comment->id) }}" method="POST">
                                                    @csrf
                                                    <div class="flex flex-col sm:flex-row gap-2 w-full">
                                                        <input type="text" name="content" 
                                                               class="flex-1 min-w-0 rounded-xl bg-slate-950 border border-slate-800 p-3 text-sm text-white focus:border-emerald-500 outline-none" 
                                                               placeholder="Balas komentar...">
                                                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-500 flex-shrink-0">
                                                            Kirim
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endauth

                                    <!-- Replies -->
                                    @if($comment->visibleReplies && $comment->visibleReplies->count() > 0)
                                        <div class="mt-6 space-y-4 border-l-2 border-slate-800 pl-4 ml-2">
                                            @foreach($comment->visibleReplies as $reply)
                                                <div class="flex gap-2 md:gap-3 w-full">
                                                    @if($reply->user?->avatar_url)
                                                        <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                                                    @else
                                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                            {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div class="flex-1 min-w-0 bg-slate-950 p-3 md:p-4 rounded-xl border border-slate-800">
                                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                                                            <h6 class="text-xs font-bold text-white truncate">{{ $reply->user->name ?? 'Anonymous' }}</h6>
                                                            <span class="text-[10px] text-slate-600 font-bold flex-shrink-0">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-slate-400 leading-relaxed break-words overflow-wrap-anywhere">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <p class="text-slate-600 font-medium">Belum ada komentar. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>
    </div>
@endsection