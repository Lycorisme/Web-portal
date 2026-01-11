@extends('layouts.public-layout')

@section('meta_title', 'Berita')

@section('content')
    <!-- Header / Banner -->
    <div class="pt-12 pb-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <h1 class="text-3xl lg:text-5xl font-extrabold text-white mb-4 tracking-tight">
                @if(request('kategori'))
                    Kategori: <span class="text-emerald-500">{{ ucwords(str_replace('-', ' ', request('kategori'))) }}</span>
                @elseif(request('tag'))
                    Tag: <span class="text-emerald-500">{{ ucwords(str_replace('-', ' ', request('tag'))) }}</span>
                @elseif(request('q'))
                    Hasil Pencarian: <span class="text-emerald-500">"{{ request('q') }}"</span>
                @else
                    Semua Berita
                @endif
            </h1>
            <p class="text-slate-400 max-w-2xl text-sm font-bold tracking-widest uppercase">
                Temukan berita terkini dan informasi terbaru seputar kegiatan kami.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Sidebar (Filter & Search) -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Search Widget -->
                <div class="bg-slate-900/50 rounded-[32px] border border-slate-800 p-6 backdrop-blur-sm">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4">Cari Berita</h3>
                    <form action="{{ route('public.articles') }}" method="GET">
                        @if(request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Kata kunci..." class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors placeholder-slate-600">
                            <div class="absolute left-3 top-3.5 text-slate-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 w-full bg-emerald-600 text-white text-xs font-black py-3 rounded-xl hover:bg-emerald-500 transition-colors uppercase tracking-widest shadow-lg shadow-emerald-900/20">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Categories Widget -->
                <div class="bg-slate-900/50 rounded-[32px] border border-slate-800 p-6 backdrop-blur-sm">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4">Kategori</h3>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('public.articles') }}" class="flex justify-between items-center px-4 py-3 rounded-xl transition-all {{ !request('kategori') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                            <span class="text-xs font-bold uppercase tracking-wide">Semua</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('public.articles', ['kategori' => $category->slug]) }}" class="flex justify-between items-center px-4 py-3 rounded-xl transition-all {{ request('kategori') == $category->slug ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                                <span class="text-xs font-bold uppercase tracking-wide">{{ $category->name }}</span>
                                <span class="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded-md font-black">{{ $category->articles_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Listing -->
            <div class="lg:col-span-3">
                @if($articles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($articles as $article)
                            <article class="flex flex-col bg-slate-900/30 rounded-[32px] border border-slate-800 overflow-hidden hover:border-emerald-500/30 transition-all duration-300 group">
                                <div class="relative aspect-video overflow-hidden">
                                    <a href="{{ route('public.article.show', $article->slug) }}">
                                        @if($article->image_url)
                                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                        @else
                                            <div class="w-full h-full bg-slate-900 flex items-center justify-center text-slate-700 font-bold uppercase text-xs">No Image</div>
                                        @endif
                                    </a>
                                    <div class="absolute top-4 left-4">
                                        @if($article->categoryRelation)
                                            <a href="{{ route('public.articles', ['kategori' => $article->categoryRelation->slug]) }}" class="px-3 py-1 bg-slate-950/80 backdrop-blur text-[10px] font-black uppercase tracking-widest text-white rounded-full border border-slate-800 hover:bg-emerald-600 hover:border-emerald-500 transition-colors">
                                                {{ $article->categoryRelation->name }}
                                            </a>
                                        @else
                                            <span class="px-3 py-1 bg-slate-950/80 backdrop-blur text-[10px] font-black uppercase tracking-widest text-slate-400 rounded-full border border-slate-800">
                                                Uncategorized
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-8 flex-1 flex flex-col">
                                    <div class="flex items-center text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 space-x-3">
                                        <span class="">
                                            {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : '-' }}
                                        </span>
                                        <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                                        @if($article->author)
                                        <span>
                                            {{ $article->author->name }}
                                        </span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl font-extrabold text-white mb-4 leading-tight group-hover:text-emerald-400 transition-colors">
                                        <a href="{{ route('public.article.show', $article->slug) }}">
                                            {{ $article->title }}
                                        </a>
                                    </h2>
                                    <p class="text-slate-400 text-sm leading-relaxed mb-6 line-clamp-3 font-medium">
                                        {{ Str::limit(strip_tags($article->content), 120) }}
                                    </p>
                                    
                                    <div class="mt-auto pt-6 border-t border-slate-800 flex items-center justify-between">
                                        <a href="{{ route('public.article.show', $article->slug) }}" class="text-xs font-black text-emerald-500 hover:text-emerald-400 uppercase tracking-widest flex items-center gap-2">
                                            Baca Selengkapnya
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $articles->appends(request()->query())->links() }} {{-- Styling pagination might require customizing the view or just accepting default --}}
                    </div>
                @else
                    <div class="text-center py-20 bg-slate-900/50 rounded-[32px] border border-dashed border-slate-800">
                        <h3 class="text-lg font-bold text-white uppercase tracking-widest">Tidak ada artikel</h3>
                        <div class="mt-6">
                            <a href="{{ route('public.articles') }}" class="inline-flex items-center px-6 py-2 border border-slate-700 shadow-sm text-xs font-bold uppercase tracking-widest rounded-xl text-white hover:bg-slate-800 transition-all">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
