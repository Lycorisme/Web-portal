@extends('public.layouts.public')

@section('meta_title', 'Berita & Artikel')

@section('content')
    <div class="relative pt-32 pb-8 md:pb-12 px-4 md:px-6 max-w-7xl mx-auto min-h-screen">
        
        {{-- Header Section (Matches Gallery) --}}
        <div class="text-center mb-16 relative z-10">
             <div class="inline-block animate-float-conserve">
                <span class="px-5 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md text-emerald-400 text-xs font-bold uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/10">
                    Berita & Artikel
                </span>
             </div>
            <h1 class="mt-8 text-4xl md:text-6xl lg:text-7xl font-display font-bold text-white leading-tight tracking-tight">
                @if(request('kategori'))
                    Kategori <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">{{ ucwords(str_replace('-', ' ', request('kategori'))) }}</span>
                @elseif(request('tag'))
                    Tag <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">{{ ucwords(str_replace('-', ' ', request('tag'))) }}</span>
                @elseif(request('q'))
                    Hasil <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">"{{ request('q') }}"</span>
                @else
                    Berita <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">Terkini</span>
                @endif
            </h1>
            <p class="mt-6 text-slate-400 max-w-2xl mx-auto text-sm md:text-base font-medium leading-relaxed">
                Temukan informasi terbaru, panduan mendalam, dan berita seputar kegiatan kami dalam satu tempat.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12 relative z-10">
            
            <!-- Sidebar (Filter & Search) -->
            <div class="order-2 lg:order-1 lg:col-span-1 space-y-6 lg:space-y-8">
                <!-- Search Widget -->
                <div class="bg-slate-900/50 rounded-[32px] border border-white/5 p-6 backdrop-blur-sm shadow-xl">
                    <h3 class="text-xs font-bold text-white uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <i class="fas fa-search text-emerald-500"></i> Cari Berita
                    </h3>
                    <form action="{{ route('public.articles') }}" method="GET">
                        @if(request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                        <div class="relative group">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Kata kunci..." class="w-full pl-10 pr-4 py-3.5 bg-slate-950/50 border border-white/10 rounded-2xl text-slate-200 text-sm font-bold focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all placeholder-slate-600 group-hover:bg-slate-950/80">
                            <div class="absolute left-3.5 top-4 text-slate-500 group-hover:text-emerald-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 w-full bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-xs font-black py-4 rounded-xl hover:from-emerald-500 hover:to-emerald-400 transition-all uppercase tracking-widest shadow-lg shadow-emerald-900/20 hover:shadow-emerald-500/25 transform hover:-translate-y-0.5">
                            Temukan
                        </button>
                    </form>
                </div>

                <!-- Categories Widget -->
                <div class="bg-slate-900/50 rounded-[32px] border border-white/5 p-6 backdrop-blur-sm shadow-xl">
                    <h3 class="text-xs font-bold text-white uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <i class="fas fa-layer-group text-emerald-500"></i> Kategori
                    </h3>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('public.articles') }}" wire:navigate class="flex justify-between items-center px-4 py-3.5 rounded-2xl transition-all {{ !request('kategori') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white border border-transparent hover:border-white/5' }}">
                            <span class="text-xs font-bold uppercase tracking-wide">Semua Artikel</span>
                            @if(!request('kategori')) <i class="fas fa-check text-[10px]"></i> @endif
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('public.articles', ['kategori' => $category->slug]) }}" wire:navigate class="flex justify-between items-center px-4 py-3.5 rounded-2xl transition-all {{ request('kategori') == $category->slug ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white border border-transparent hover:border-white/5' }}">
                                <span class="text-xs font-bold uppercase tracking-wide">{{ $category->name }}</span>
                                <span class="text-[10px] {{ request('kategori') == $category->slug ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-800 text-slate-400' }} px-2 py-0.5 rounded-md font-black min-w-[24px] text-center">{{ $category->articles_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Listing -->
            <div class="order-1 lg:order-2 lg:col-span-3">
                @if($articles->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-4 md:gap-6">
                        @foreach($articles as $article)
                            @php
                                $idx = $loop->index;
                                $mod = $idx % 8; // Cycle pattern every 8 items
                                
                                // Default classes - responsive grid tailored for news
                                $colClass = 'sm:col-span-1 md:col-span-2'; 
                                $heightClass = 'min-h-[280px] md:min-h-[320px]';
                                $showExcerpt = false;
                                $titleSize = 'text-base md:text-lg';
                                
                                if ($mod == 0) {
                                    $colClass = 'sm:col-span-2 md:col-span-6'; // Full width featured
                                    $heightClass = 'min-h-[320px] md:min-h-[450px]';
                                    $showExcerpt = true;
                                    $titleSize = 'text-xl md:text-3xl lg:text-4xl';
                                } elseif ($mod == 1 || $mod == 2) {
                                    $colClass = 'sm:col-span-1 md:col-span-3'; // Half width
                                    $heightClass = 'min-h-[280px] md:min-h-[380px]';
                                    $titleSize = 'text-lg md:text-2xl';
                                    $showExcerpt = true;
                                } elseif ($mod == 6) {
                                    $colClass = 'sm:col-span-2 md:col-span-4'; // 2/3 width
                                    $heightClass = 'min-h-[280px] md:min-h-[350px]';
                                    $titleSize = 'text-lg md:text-2xl';
                                    $showExcerpt = true;
                                } elseif ($mod == 7) {
                                    $colClass = 'sm:col-span-2 md:col-span-2'; // 1/3 width
                                    $heightClass = 'min-h-[280px] md:min-h-[350px]';
                                }
                            @endphp

                            <article class="{{ $colClass }} {{ $heightClass }} group relative rounded-[32px] overflow-hidden border border-white/5 bg-slate-900 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 hover:-translate-y-2 hover:border-emerald-500/30">
                                <!-- Background Image -->
                                <div class="absolute inset-0">
                                    @if($article->image_url)
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                            <span class="text-slate-700 font-bold uppercase tracking-widest text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent opacity-90 transition-opacity duration-500 group-hover:opacity-80"></div>
                                </div>

                                <!-- Content -->
                                <div class="absolute inset-0 p-6 md:p-8 flex flex-col justify-end">
                                    <div class="transform transition-transform duration-500 translate-y-4 group-hover:translate-y-0">
                                        <!-- Header: Category & Date -->
                                        <div class="flex items-center gap-3 mb-3 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-md">
                                                {{ $article->categoryRelation?->name ?? 'News' }}
                                            </span>
                                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest hidden sm:inline flex items-center gap-1">
                                                <i class="far fa-calendar-alt"></i> {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : '' }}
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h2 class="{{ $titleSize }} font-extrabold text-white leading-tight mb-3 group-hover:text-emerald-400 transition-colors">
                                            <a href="{{ route('public.article.show', $article->slug) }}" wire:navigate class="focus:outline-none">
                                                <span class="absolute inset-0 z-10"></span>
                                                {{ $article->title }}
                                            </a>
                                        </h2>

                                        <!-- Excerpt (Conditional) -->
                                        @if($showExcerpt)
                                            <p class="text-slate-300/90 text-sm font-medium leading-relaxed line-clamp-2 trace-wide mb-4 hidden md:block opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-200">
                                                {{ Str::limit(strip_tags($article->content), 120) }}
                                            </p>
                                        @endif

                                        <!-- Action -->
                                        <div class="flex items-center gap-2 text-emerald-400 text-xs font-black uppercase tracking-widest opacity-100 md:opacity-0 transform translate-y-0 md:translate-y-2 md:group-hover:opacity-100 md:group-hover:translate-y-0 transition-all duration-500 delay-300">
                                            Baca Selengkapnya <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-slate-900/30 rounded-[40px] border border-dashed border-slate-800 backdrop-blur-sm relative overflow-hidden">
                         <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900/50"></div>
                        <div class="relative z-10">
                            <i class="far fa-newspaper text-6xl text-slate-800 mb-6 block animate-float"></i>
                            <h3 class="text-xl font-bold text-white uppercase tracking-widest mb-2">Ops, Berita Tidak Ditemukan</h3>
                            <p class="text-slate-500 text-sm mb-8 max-w-md mx-auto">Kami tidak dapat menemukan apa yang Anda cari. Coba gunakan kata kunci lain atau reset filter Anda.</p>
                            <a href="{{ route('public.articles') }}" wire:navigate class="inline-flex items-center px-8 py-3 rounded-xl bg-slate-800 text-white font-bold uppercase tracking-widest text-xs hover:bg-emerald-600 transition-all shadow-lg hover:shadow-emerald-500/25 border border-white/5 hover:border-emerald-500/50">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
