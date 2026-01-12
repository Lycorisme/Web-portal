@extends('public.layouts.public')

@section('meta_title', 'Berita')

@section('content')
    <!-- Header / Banner -->
    <div class="pt-32 md:pt-40 pb-6 md:pb-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 md:px-6 relative z-10">
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-display font-bold text-white mb-6 tracking-tight leading-tight">
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

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-8 md:py-12 overflow-x-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
            
            <!-- Sidebar (Filter & Search) - appears at bottom on mobile -->
            <div class="order-2 lg:order-1 lg:col-span-1 space-y-6 lg:space-y-8">
                <!-- Search Widget -->
                <div class="bg-slate-900/50 rounded-2xl md:rounded-[32px] border border-slate-800 p-4 md:p-6 backdrop-blur-sm">
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
                <div class="bg-slate-900/50 rounded-2xl md:rounded-[32px] border border-slate-800 p-4 md:p-6 backdrop-blur-sm">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4">Kategori</h3>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('public.articles') }}" wire:navigate class="flex justify-between items-center px-4 py-3 rounded-xl transition-all {{ !request('kategori') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                            <span class="text-xs font-bold uppercase tracking-wide">Semua</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('public.articles', ['kategori' => $category->slug]) }}" wire:navigate class="flex justify-between items-center px-4 py-3 rounded-xl transition-all {{ request('kategori') == $category->slug ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                                <span class="text-xs font-bold uppercase tracking-wide">{{ $category->name }}</span>
                                <span class="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded-md font-black">{{ $category->articles_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Listing - appears first on mobile -->
            <div class="order-1 lg:order-2 lg:col-span-3">
                @if($articles->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-4 md:gap-6">
                        @foreach($articles as $article)
                            @php
                                $idx = $loop->index;
                                $mod = $idx % 8; // Cycle pattern every 8 items
                                
                                // Default classes - mobile first approach
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
                                // Items 3, 4, 5 are defaults (span 2, small)
                            @endphp

                            <article class="{{ $colClass }} {{ $heightClass }} relative group rounded-2xl md:rounded-[32px] overflow-hidden border border-slate-800 bg-slate-900 shadow-xl md:shadow-2xl hover:shadow-emerald-900/20 transition-all duration-500 hover:-translate-y-1">
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
                                <div class="absolute inset-0 p-4 md:p-8 flex flex-col justify-end">
                                    <div class="transform transition-transform duration-500 translate-y-2 md:translate-y-4 group-hover:translate-y-0">
                                        <!-- Header: Category & Date -->
                                        <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                            <span class="px-2 md:px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest backdrop-blur-md">
                                                {{ $article->categoryRelation?->name ?? 'News' }}
                                            </span>
                                            <span class="text-[9px] md:text-[10px] text-slate-300 font-bold uppercase tracking-widest hidden sm:inline">
                                                 {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : '' }}
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h2 class="{{ $titleSize }} font-extrabold text-white leading-tight mb-2 group-hover:text-emerald-400 transition-colors">
                                            <a href="{{ route('public.article.show', $article->slug) }}" wire:navigate class="focus:outline-none">
                                                <span class="absolute inset-0 z-10"></span>
                                                {{ $article->title }}
                                            </a>
                                        </h2>

                                        <!-- Excerpt (Conditional) -->
                                        @if($showExcerpt)
                                            <p class="text-slate-300/80 text-sm font-medium leading-relaxed line-clamp-2 mb-4 hidden md:block opacity-80">
                                                {{ Str::limit(strip_tags($article->content), 100) }}
                                            </p>
                                        @endif

                                        <!-- Action -->
                                        <div class="flex items-center gap-2 text-emerald-500 text-xs font-black uppercase tracking-widest opacity-100 md:opacity-0 transform translate-y-0 md:translate-y-4 md:group-hover:opacity-100 md:group-hover:translate-y-0 transition-all duration-500 delay-200">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </div>
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
                    <div class="text-center py-12 md:py-20 bg-slate-900/50 rounded-2xl md:rounded-[32px] border border-dashed border-slate-800">
                        <h3 class="text-base md:text-lg font-bold text-white uppercase tracking-widest">Tidak ada artikel</h3>
                        <div class="mt-6">
                            <a href="{{ route('public.articles') }}" wire:navigate class="inline-flex items-center px-6 py-2 border border-slate-700 shadow-sm text-xs font-bold uppercase tracking-widest rounded-xl text-white hover:bg-slate-800 transition-all">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
