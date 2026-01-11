@extends('layouts.public-layout')

@section('meta_title', 'Beranda')

@section('content')

    <header class="max-w-7xl mx-auto px-6 pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-[500px]">
            <!-- Hero Main -->
            <div class="lg:col-span-8 relative group cursor-pointer overflow-hidden rounded-[40px] border border-slate-800 shadow-2xl shadow-emerald-500/5"
                 onclick="window.location.href='{{ $featuredArticle ? route('public.article.show', $featuredArticle->slug) : '#' }}'">
                @if($featuredArticle && $featuredArticle->image_url)
                    <img src="{{ $featuredArticle->image_url }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                @else
                    <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                        <span class="text-slate-700 font-bold uppercase tracking-widest">No Image</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="absolute bottom-0 p-10 space-y-4">
                    @if($featuredArticle)
                        <span class="px-4 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-full uppercase tracking-widest">
                            {{ $featuredArticle->categoryRelation?->name ?? 'TERBARU' }}
                        </span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
                            {{ $featuredArticle->title }}
                        </h2>
                        <div class="flex items-center gap-6 text-slate-400 text-sm font-semibold">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg> 
                                {{ $featuredArticle->author->name ?? 'Admin' }}
                            </span>
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg> 
                                {{ $featuredArticle->views }} Views
                            </span>
                        </div>
                    @else
                        <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">Belum ada berita utama</h2>
                    @endif
                </div>
            </div>

            <!-- Hero Side (2 items) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                @php $sideArticles = $latestArticles->take(2); @endphp
                @foreach($sideArticles as $article)
                    <div class="flex-1 relative group overflow-hidden rounded-[32px] border border-slate-800 cursor-pointer"
                         onclick="window.location.href='{{ route('public.article.show', $article->slug) }}'">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="w-full h-full bg-slate-900 border border-slate-800 flex items-center justify-center">
                                <span class="text-slate-700 font-bold uppercase tracking-widest text-xs">No Image</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-slate-950/60 p-6 flex flex-col justify-end">
                            <h3 class="text-white font-bold leading-snug group-hover:text-emerald-400 transition-colors line-clamp-3">
                                {{ $article->title }}
                            </h3>
                            <p class="text-[10px] text-emerald-500 font-black mt-2 tracking-widest uppercase">
                                {{ $article->categoryRelation?->name ?? 'Update' }}
                            </p>
                        </div>
                    </div>
                @endforeach
                
                @for($i = 0; $i < (2 - $sideArticles->count()); $i++)
                     <div class="flex-1 relative group overflow-hidden rounded-[32px] border border-slate-800 bg-slate-900/30 flex flex-col justify-end p-6">
                        <div class="absolute inset-0 flex items-center justify-center opacity-20">
                            <svg class="w-16 h-16 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                        </div>
                        <h3 class="text-slate-500 font-bold leading-snug z-10">Berita Segera Hadir</h3>
                        <p class="text-[10px] text-emerald-500 font-black mt-2 tracking-widest uppercase z-10">BTIKP UPDATE</p>
                     </div>
                @endfor
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Main Feed -->
        <div class="lg:col-span-8 space-y-12">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full animate-ping"></span>
                    </span>
                    JELAJAHI BERITA
                </h3>
                <div class="flex gap-2 text-xs font-bold text-slate-500">
                    <a href="{{ route('public.articles') }}" class="text-white bg-slate-800 px-4 py-1.5 rounded-full border border-slate-700 hover:bg-slate-700 transition">Semua</a>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Logic: If we have very few articles, don't skip them in the main feed to avoid empty space. -->
                @php 
                    $mainFeedArticles = $latestArticles->count() > 2 ? $latestArticles->skip(2) : $latestArticles; 
                @endphp
                
                @foreach($mainFeedArticles as $article)
                    <div class="group flex flex-col md:flex-row gap-8 p-6 rounded-[32px] transition-all duration-500 hover:bg-slate-900/30 border border-transparent hover:border-slate-800">
                        <div class="w-full md:w-64 h-48 rounded-2xl overflow-hidden shrink-0 shadow-xl border border-slate-800 relative">
                             @if($article->image_url)
                                <img src="{{ $article->image_url }}" class="w-full h-full object-cover transition group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-slate-900 flex items-center justify-center text-slate-700 font-bold text-xs uppercase">No Image</div>
                            @endif
                        </div>
                        <div class="flex flex-col justify-between flex-1">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-[10px] font-black px-3 py-1 bg-violet-500/10 text-violet-400 border border-violet-500/20 rounded-full uppercase tracking-widest">
                                        {{ $article->categoryRelation?->name ?? 'Berita' }}
                                    </span>
                                    <span class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">
                                        {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : '-' }}
                                    </span>
                                </div>
                                <h4 class="text-xl md:text-2xl font-extrabold text-white group-hover:text-emerald-400 transition-all leading-snug mb-3">
                                    <a href="{{ route('public.article.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h4>
                                <p class="text-slate-400 text-sm leading-relaxed line-clamp-2">
                                    {{ Str::limit(strip_tags($article->content), 130) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-4 mt-6">
                                <span class="text-xs font-bold text-emerald-500 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Security Passed
                                </span>
                                <span class="text-slate-700">|</span>
                                <a href="{{ route('public.article.show', $article->slug) }}" class="text-xs font-bold text-white uppercase tracking-widest hover:text-emerald-500 transition-all flex items-center gap-2">
                                    Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($mainFeedArticles->count() == 0 && $latestArticles->count() > 0 && $latestArticles->take(2)->count() > 0)
                   {{-- If we have articles but all are in hero side (less than 3 total) --}}
                   {{-- Do nothing or show message --}}
                @elseif($latestArticles->count() == 0)
                    <div class="text-center py-10 text-slate-500 font-bold">Belum ada berita saat ini.</div>
                @endif
            </div>
            
            <div class="flex justify-center pt-10">
                <a href="{{ route('public.articles') }}" class="px-12 py-4 rounded-2xl bg-slate-900 border border-slate-800 text-white font-bold hover:bg-emerald-600 transition-all shadow-xl uppercase text-xs tracking-widest">
                    Muat Berita Lainnya
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-12">
            <!-- Widget 1: Keamanan -->
            <div class="p-8 rounded-[40px] bg-gradient-to-br from-emerald-600 to-teal-700 border border-emerald-400/30 shadow-2xl shadow-emerald-500/20">
                <svg class="w-12 h-12 text-white mb-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"></path></svg>
                <h4 class="text-2xl font-black text-white leading-tight mb-3 italic">KEAMANAN<br>TERJAMIN</h4>
                <p class="text-emerald-100 text-xs leading-relaxed font-medium">Setiap interaksi dan konten di portal ini telah divalidasi oleh sistem keamanan berlapis BTIKP.</p>
            </div>

            <!-- Widget 2: Paling Disukai -->
            <div class="space-y-6">
                <h3 class="text-sm font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-3">
                    PALING DISUKAI <span class="flex-1 h-px bg-slate-800"></span>
                </h3>
                <div class="space-y-6">
                    @forelse($popularArticles as $article)
                        <div class="flex gap-4 group cursor-pointer" onclick="window.location.href='{{ route('public.article.show', $article->slug) }}'">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 border border-slate-800">
                                @if($article->image_url)
                                    <img src="{{ $article->image_url }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                                @else
                                    <div class="w-full h-full bg-slate-900"></div>
                                @endif
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-white group-hover:text-emerald-400 leading-snug transition-colors line-clamp-2">
                                    {{ $article->title }}
                                </h5>
                                <span class="text-[10px] font-black text-rose-500 mt-2 flex items-center gap-1.5 uppercase">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg> 
                                    {{ $article->likes_count ?? 0 }} Likes
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-600 font-bold italic">Belum ada data</p>
                    @endforelse
                </div>
            </div>

            <!-- Widget 3: Tag Populer -->
            <div class="space-y-6">
                <h3 class="text-sm font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-3">
                    TAG POPULER <span class="flex-1 h-px bg-slate-800"></span>
                </h3>
                <div class="flex flex-wrap gap-2">
                    @isset($popularTags)
                        @foreach($popularTags as $tag)
                            <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" class="px-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-[10px] font-black text-slate-400 hover:text-white hover:border-emerald-500 transition-all cursor-pointer uppercase">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    @else
                         <span class="text-xs text-slate-600">Tags loading...</span>
                    @endisset
                </div>
            </div>
        </div>
    </main>

@endsection
