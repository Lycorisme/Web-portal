@extends('public.layouts.public')

@section('meta_title', 'Beranda')

@section('content')

    <header class="max-w-7xl mx-auto px-6 pt-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-[500px]">
            <!-- Hero Main -->
            <!-- Hero Main (Slider) -->
            <div x-data='{ 
                    activeSlide: 0,
                    slides: {!! $featuredArticles->map(fn($a) => [
                        "img" => $a->image_url,
                        "title" => $a->title,
                        "category" => $a->categoryRelation?->name ?? "TERBARU", 
                        "author" => $a->author->name ?? "Admin",
                        "views" => $a->views,
                        "url" => route("public.article.show", $a->slug)
                    ])->toJson(JSON_HEX_APOS) !!},
                    interval: null,
                    next() {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                    },
                    startAutoPlay() {
                        this.interval = setInterval(() => this.next(), 5000);
                    },
                    stopAutoPlay() {
                        clearInterval(this.interval);
                    }
                }' 
                x-init="startAutoPlay()"
                @mouseenter="stopAutoPlay()"
                @mouseleave="startAutoPlay()"
                class="lg:col-span-8 relative group overflow-hidden rounded-[40px] border border-slate-800 shadow-2xl shadow-emerald-500/5 block min-h-[500px]"
            >
                <template x-for="(slide, index) in slides" :key="index">
                    <a :href="slide.url" wire:navigate 
                       x-show="activeSlide === index"
                       x-transition:enter="transition transform ease-out duration-700"
                       x-transition:enter-start="opacity-0 translate-x-10"
                       x-transition:enter-end="opacity-100 translate-x-0"
                       x-transition:leave="transition transform ease-in duration-500 absolute inset-0"
                       x-transition:leave-start="opacity-100 translate-x-0"
                       x-transition:leave-end="opacity-0 -translate-x-10"
                       class="absolute inset-0 w-full h-full block">
                        
                        <!-- Image -->
                        <template x-if="slide.img">
                             <img :src="slide.img" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                        </template>
                        <template x-if="!slide.img">
                             <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                <span class="text-slate-700 font-bold uppercase tracking-widest">No Image</span>
                             </div>
                        </template>

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                        
                        <!-- Content -->
                        <div class="absolute bottom-0 p-10 space-y-4 max-w-3xl">
                             <span class="px-4 py-1 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-[10px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-emerald-500/20" x-text="slide.category"></span>
                             <h2 class="text-3xl md:text-5xl font-display font-bold text-white leading-tight" x-text="slide.title"></h2>
                             <div class="flex items-center gap-6 text-slate-400 text-sm font-semibold">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-user-circle text-emerald-500"></i>
                                    <span x-text="slide.author"></span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-eye text-emerald-500"></i>
                                    <span x-text="slide.views + ' Views'"></span>
                                </span>
                            </div>
                        </div>
                    </a>
                </template>

                <!-- Navigation Dots -->
                <div class="absolute bottom-8 right-8 flex gap-2 z-20">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index" 
                                class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                :class="activeSlide === index ? 'bg-emerald-500 w-8' : 'bg-slate-600 hover:bg-slate-400'">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Hero Side (2 items) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                @php 
                    // Take top 2 for side widget
                    $sideArticles = $latestArticles->take(2); 
                @endphp

                @foreach($sideArticles as $article)
                    <a href="{{ route('public.article.show', $article->slug) }}" wire:navigate class="block flex-1 relative group overflow-hidden rounded-[32px] border border-slate-800 hover:border-emerald-500/30 transition-colors">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="w-full h-full bg-slate-900 border border-slate-800 flex items-center justify-center">
                                <span class="text-slate-700 font-bold uppercase tracking-widest text-xs">No Image</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 to-transparent p-6 flex flex-col justify-end">
                            <h3 class="text-white font-bold leading-snug group-hover:text-emerald-400 transition-colors line-clamp-3 font-display">
                                {{ $article->title }}
                            </h3>
                            <p class="text-[10px] text-emerald-500 font-black mt-2 tracking-widest uppercase">
                                {{ $article->categoryRelation?->name ?? 'Update' }}
                            </p>
                        </div>
                    </a>
                @endforeach
                
                @for($i = 0; $i < (2 - $sideArticles->count()); $i++)
                     <div class="flex-1 relative group overflow-hidden rounded-[32px] border border-slate-800 bg-slate-900/30 flex flex-col justify-end p-6">
                        <div class="absolute inset-0 flex items-center justify-center opacity-20">
                            <i class="fas fa-newspaper text-6xl text-emerald-500 animate-pulse"></i>
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
            <style>
                .hide-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                .hide-scrollbar {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>
            <div class="flex items-center justify-between gap-6 border-b border-slate-800 pb-4 overflow-hidden">
                <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-3 shrink-0 font-display">
                    <span class="w-8 h-8 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full animate-ping"></span>
                    </span>
                    JELAJAHI BERITA
                </h3>
                
                <div class="flex-1 overflow-x-auto hide-scrollbar">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 whitespace-nowrap min-w-max px-2">
                        <a href="{{ route('public.articles') }}" wire:navigate class="text-white bg-slate-800 px-4 py-1.5 rounded-full border border-slate-700 hover:bg-slate-700 transition">Semua</a>
                        @foreach($categories as $category)
                             <a href="{{ route('public.articles', ['kategori' => $category->slug]) }}" wire:navigate
                                class="text-slate-400 bg-slate-900/50 hover:bg-slate-800 hover:text-white px-4 py-1.5 rounded-full border border-slate-800 hover:border-slate-700 transition">
                                {{ $category->name }}
                             </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Logic: Take articles starting from index 2 (skipping the first 2 used in Hero Side) -->
                @php 
                    $mainFeedArticles = $latestArticles->slice(2); 
                @endphp
                
                @foreach($mainFeedArticles as $article)
                    <div class="group flex flex-col md:flex-row gap-8 p-6 rounded-[32px] transition-all duration-500 hover:bg-slate-900/40 border border-transparent hover:border-slate-800 hover:shadow-2xl hover:translate-y-[-4px]">
                        <div class="w-full md:w-64 h-48 rounded-2xl overflow-hidden shrink-0 shadow-xl border border-slate-800 relative">
                             @if($article->image_url)
                                <img src="{{ $article->image_url }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
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
                                <h4 class="text-xl md:text-2xl font-bold text-white group-hover:text-emerald-400 transition-all leading-snug mb-3 font-display">
                                    <a href="{{ route('public.article.show', $article->slug) }}" wire:navigate>
                                        {{ $article->title }}
                                    </a>
                                </h4>
                                <p class="text-slate-400 text-sm leading-relaxed line-clamp-2">
                                    {{ Str::limit(strip_tags($article->content), 130) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-4 mt-6">
                                <span class="text-xs font-bold text-emerald-500 flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Terverifikasi
                                </span>
                                <span class="text-slate-700">|</span>
                                <a href="{{ route('public.article.show', $article->slug) }}" wire:navigate class="text-xs font-bold text-white uppercase tracking-widest hover:text-emerald-500 transition-all flex items-center gap-2">
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
            
            <div class="flex justify-center pt-10 w-full clear-both block">
                <a href="{{ route('public.articles') }}" wire:navigate class="px-12 py-4 rounded-2xl bg-slate-900 border border-slate-800 text-white font-bold hover:bg-emerald-600 transition-all shadow-xl uppercase text-xs tracking-widest">
                    Muat Berita Lainnya
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-12">

            <!-- Widget 2: Paling Disukai -->
            <div class="space-y-6">
                <h3 class="text-sm font-black text-slate-500 uppercase tracking-[0.2em] flex items-center gap-3">
                    PALING DISUKAI <span class="flex-1 h-px bg-slate-800"></span>
                </h3>
                <div class="space-y-6">
                    @forelse($popularArticles as $article)
                        <a href="{{ route('public.article.show', $article->slug) }}" wire:navigate class="flex gap-4 group">
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
                        </a>
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
                            <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" wire:navigate class="px-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-[10px] font-black text-slate-400 hover:text-white hover:border-emerald-500 transition-all cursor-pointer uppercase">
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
