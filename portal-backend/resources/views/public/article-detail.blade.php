@extends('layouts.public-layout')

@section('meta_title', $article->title)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 160))

@section('content')
    <!-- Progress Bar -->
    <div x-data="{ width: '0%' }" 
         @scroll.window="width = ((window.pageYOffset) / (document.documentElement.scrollHeight - window.innerHeight) * 100) + '%'"
         class="fixed top-0 left-0 h-1 z-[60] bg-emerald-500 transition-all duration-100" 
         :style="'width: ' + width"></div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Main Content -->
            <article class="lg:col-span-3">
                <!-- Breadcrumbs -->
                <nav class="flex mb-8 text-xs font-bold uppercase tracking-widest text-slate-500" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('public.home') }}" class="hover:text-emerald-500 transition-colors">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="mx-2 text-slate-700">/</span>
                                @if($article->categoryRelation)
                                    <a href="{{ route('public.articles', ['kategori' => $article->categoryRelation->slug]) }}" class="hover:text-emerald-500 transition-colors">
                                        {{ $article->categoryRelation->name }}
                                    </a>
                                @else
                                    <a href="{{ route('public.articles') }}" class="hover:text-emerald-500 transition-colors">
                                        Umum
                                    </a>
                                @endif
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <span class="mx-2 text-slate-700">/</span>
                                <span class="text-slate-300 line-clamp-1 border-b border-transparent">
                                    {{ Str::limit($article->title, 30) }}
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header -->
                <header class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-4 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {{ $article->categoryRelation?->name ?? 'Umum' }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-slate-700 rounded-full"></span>
                            {{ \Carbon\Carbon::parse($article->published_at)->format('d F Y') }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-8 leading-tight tracking-tight">
                        {{ $article->title }}
                    </h1>

                    <div class="flex items-center justify-between border-y border-slate-800 py-6">
                        <div class="flex items-center">
                            @if($article->author)
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-slate-800 flex items-center justify-center text-lg font-bold text-slate-400 border border-slate-700">
                                        {{ substr($article->author->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-white">{{ $article->author->name }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Penulis</div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <button class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:border-emerald-500/50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($article->image_url)
                    <figure class="mb-12 rounded-[32px] overflow-hidden shadow-2xl shadow-emerald-900/10 border border-slate-800">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-auto object-cover max-h-[600px]">
                    </figure>
                @endif

                <!-- Article Content -->
                <div class="prose prose-lg prose-invert max-w-none text-slate-300 font-medium leading-loose">
                    {!! $article->content !!}
                </div>

                <!-- Tags -->
                @if($article->tags && $article->tags->count() > 0)
                    <div class="mt-12 flex flex-wrap gap-2 pb-8 border-b border-slate-800">
                        @foreach($article->tags as $tag)
                            <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" class="px-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-[10px] font-black text-slate-400 hover:text-white hover:border-emerald-500 transition-all cursor-pointer uppercase tracking-widest">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Engagement Section -->
                <div class="mt-8 py-8 flex items-center justify-between">
                    <div class="flex items-center gap-6" x-data="{ liked: {{ $hasLiked ? 'true' : 'false' }}, count: {{ $article->likes_count ?? 0 }} }">
                        <form action="{{ route('public.article.like', $article->id) }}" method="POST" @submit.prevent="liked = !liked; count = liked ? count + 1 : count - 1; fetch($el.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })">
                            <button type="submit" 
                                    class="flex items-center gap-3 px-6 py-3 rounded-xl transition-all duration-300 border"
                                    :class="liked ? 'bg-rose-500/10 border-rose-500/50 text-rose-500' : 'bg-slate-900 border-slate-800 text-slate-400 hover:bg-slate-800 hover:text-white'">
                                <svg class="w-6 h-6" :class="liked ? 'fill-current' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <span class="font-black text-sm" x-text="count"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-12 p-8 bg-slate-900/30 rounded-[32px] border border-slate-800" id="comments">
                    <h3 class="text-xl font-black text-white uppercase tracking-widest mb-8 flex items-center gap-3">
                        Komentar <span class="px-3 py-1 bg-slate-800 rounded-full text-xs">{{ $article->visibleComments->count() }}</span>
                    </h3>
                    
                    <!-- Comment Form -->
                    @auth
                        <form action="{{ route('public.article.comment', $article->id) }}" method="POST" class="mb-12">
                            @csrf
                            <div class="relative">
                                <textarea name="content" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-2xl p-4 text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none font-medium" placeholder="Tulis pendapat Anda di sini..."></textarea>
                                <div class="absolute bottom-4 right-4">
                                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-xs font-black rounded-xl hover:bg-emerald-500 transition-colors uppercase tracking-widest shadow-lg shadow-emerald-900/20">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="bg-slate-950 rounded-2xl p-8 text-center mb-10 border border-slate-800 border-dashed">
                            <p class="text-slate-400 font-bold mb-4 text-sm">Silakan masuk untuk berdiskusi.</p>
                            <a href="{{ route('login') }}" class="inline-block px-6 py-2.5 bg-slate-800 text-white text-xs font-black rounded-xl hover:bg-slate-700 transition-colors uppercase tracking-widest">
                                Masuk / Daftar
                            </a>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="space-y-8">
                        @forelse($article->visibleComments as $comment)
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-slate-400 border border-slate-700">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-slate-950 border border-slate-800 p-5 rounded-2xl rounded-tl-none">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="font-bold text-white text-sm">{{ $comment->user->name }}</h5>
                                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-slate-400 text-sm leading-relaxed">{{ $comment->content }}</p>
                                    </div>

                                    <!-- Replies -->
                                    @if($comment->visibleReplies && $comment->visibleReplies->count() > 0)
                                        <div class="mt-4 ml-4 space-y-4 border-l-2 border-slate-800 pl-4">
                                            @foreach($comment->visibleReplies as $reply)
                                                <div class="flex space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-700">
                                                            {{ substr($reply->user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 bg-slate-950 p-4 rounded-xl border border-slate-800">
                                                        <div class="flex items-center justify-between mb-1">
                                                            <h6 class="text-xs font-bold text-white">{{ $reply->user->name }}</h6>
                                                            <span class="text-[10px] text-slate-600 font-bold uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-slate-400 leading-relaxed">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    @auth
                                    <div x-data="{ open: false }" class="mt-2 text-right">
                                        <button @click="open = !open" class="text-[10px] font-bold text-slate-500 hover:text-emerald-500 uppercase tracking-widest">Balas</button>
                                        <div x-show="open" class="mt-3">
                                            <form action="{{ route('public.comment.reply', $comment->id) }}" method="POST">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <input type="text" name="content" class="flex-1 rounded-xl bg-slate-950 border-slate-800 p-2 text-xs text-white focus:border-emerald-500 outline-none" placeholder="Balas komentar...">
                                                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-[10px] font-black rounded-xl hover:bg-emerald-500 uppercase tracking-widest">Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-600 italic text-sm font-medium">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-8">
                <div class="bg-slate-900/50 rounded-[32px] border border-slate-800 p-6 backdrop-blur-sm sticky top-24">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-6 pb-4 border-b border-slate-800">Berita Terkait</h3>
                    <div class="space-y-6">
                        @foreach($relatedArticles as $related)
                            <div class="group flex flex-col gap-3 cursor-pointer" onclick="window.location.href='{{ route('public.article.show', $related->slug) }}'">
                                <div class="relative aspect-video rounded-xl overflow-hidden border border-slate-800">
                                    @if($related->image_url)
                                        <img src="{{ $related->image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full bg-slate-900 flex items-center justify-center text-[10px] text-slate-700 font-black uppercase">No Image</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white leading-snug group-hover:text-emerald-400 transition-colors line-clamp-2">
                                        {{ $related->title }}
                                    </h4>
                                    <p class="text-[10px] text-slate-500 mt-2 font-bold uppercase tracking-widest">{{ $related->published_at ? \Carbon\Carbon::parse($related->published_at)->diffForHumans() : '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
