@extends('public.layouts.public')

@section('meta_title', 'Galeri')

@section('content')
    <div class="relative pt-32 pb-20 px-6 max-w-7xl mx-auto min-h-screen">
        
        {{-- Header Section --}}
        <div class="text-center mb-20 relative z-10">
             <div class="inline-block animate-float-conserve">
                <span class="px-5 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md text-emerald-400 text-xs font-bold uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/10">
                    Multimedia & Dokumentasi
                </span>
             </div>
            <h1 class="mt-8 text-5xl md:text-7xl font-display font-bold text-white leading-tight tracking-tight">
                Galeri <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">Kegiatan</span>
            </h1>
            <p class="mt-6 text-slate-400 max-w-2xl mx-auto text-sm md:text-base font-medium leading-relaxed">
                Menjelajahi momen-momen penting dan dokumentasi kegiatan terbaru BTIKP dalam visual yang menakjubkan.
            </p>
        </div>

        {{-- Prepare Gallery Items for JS --}}
        @php
            $galleryItems = $galleries->map(function($item) {
                $source = $item->media_type == 'video' ? $item->video_url : $item->image_url;
                $isYoutube = false;
                if ($item->media_type == 'video') {
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $source, $matches)) {
                        $source = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
                        $isYoutube = true;
                    }
                }
                
                return [
                    'type' => $isYoutube ? 'youtube' : $item->media_type,
                    'source' => $source,
                    'title' => $item->title,
                ];
            })->values();
        @endphp

        <div x-data="{ 
                lightboxOpen: false, 
                activeIndex: 0, 
                items: {{ Js::from($galleryItems) }},
                touchStartX: 0,
                touchEndX: 0,
                get activeItem() { return this.items[this.activeIndex] || {} },
                next() { this.activeIndex = (this.activeIndex + 1) % this.items.length },
                prev() { this.activeIndex = (this.activeIndex - 1 + this.items.length) % this.items.length },
                handleSwipe() {
                    const swipeDistance = this.touchStartX - this.touchEndX;
                    if (swipeDistance > 50) this.next();
                    if (swipeDistance < -50) this.prev();
                }
            }"
            @keydown.window.arrow-right="if(lightboxOpen) next()"
            @keydown.window.arrow-left="if(lightboxOpen) prev()"
            @keydown.window.escape="lightboxOpen = false">
            
            {{-- Filter Bar --}}
            <div class="mb-16 flex flex-wrap justify-center gap-4 relative z-10">
                <div class="p-1.5 rounded-2xl bg-slate-900/50 backdrop-blur-md border border-white/5 flex flex-wrap justify-center gap-2 shadow-2xl">
                    <a href="{{ route('public.gallery') }}" wire:navigate 
                       class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 {{ !request('album') && !request('type') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        Semua
                    </a>
                    <a href="{{ route('public.gallery', array_merge(request()->query(), ['type' => 'image'])) }}" wire:navigate 
                       class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 {{ request('type') == 'image' ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        Foto
                    </a>
                    <a href="{{ route('public.gallery', array_merge(request()->query(), ['type' => 'video'])) }}" wire:navigate 
                       class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 {{ request('type') == 'video' ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        Video
                    </a>
                    
                    {{-- Dropdown for Albums --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" 
                                class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-300 flex items-center gap-2 {{ request('album') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            {{ request('album') ? Str::limit(request('album'), 15) : 'Album' }}
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full mt-2 w-56 p-2 rounded-2xl bg-slate-900 border border-slate-700/50 shadow-xl backdrop-blur-xl z-30 max-h-60 overflow-y-auto">
                            <a href="{{ route('public.gallery', array_diff_key(request()->query(), ['album' => ''])) }}" wire:navigate 
                               class="block px-4 py-3 rounded-xl text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800 transition-colors uppercase tracking-wider">
                                Semua Album
                            </a>
                            @foreach($albums as $album)
                                <a href="{{ route('public.gallery', array_merge(request()->query(), ['album' => $album])) }}" wire:navigate 
                                   class="block px-4 py-3 rounded-xl text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800 transition-colors uppercase tracking-wider border-t border-white/5">
                                    {{ $album }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Grid -->
            @if($galleries->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach($galleries as $gallery)
                        <div class="group relative rounded-[32px] overflow-hidden aspect-[4/5] bg-slate-900 border border-white/5 cursor-pointer shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 hover:-translate-y-2 hover:border-emerald-500/30"
                             @click="
                                lightboxOpen = true; 
                                activeIndex = {{ $loop->index }};
                             ">
                            
                            {{-- Image/Thumbnail --}}
                            <div class="absolute inset-0">
                                @if($gallery->media_type == 'video' && $gallery->thumbnail_url)
                                    <img src="{{ $gallery->thumbnail_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-slate-950/30 group-hover:bg-slate-950/50 transition-colors"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-xl group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-play ml-1 text-xl"></i>
                                        </div>
                                    </div>
                                @elseif($gallery->image_url)
                                    <img src="{{ $gallery->image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                                @else
                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                        <span class="text-xs font-bold text-slate-600 uppercase">No Media</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Overlay Content --}}
                            <div class="absolute inset-0 flex flex-col justify-end p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <span class="align-self-start inline-flex px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-3 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                                    {{ $gallery->media_type == 'video' ? 'Video' : 'Foto' }}
                                </span>
                                <h3 class="text-white font-bold text-lg leading-tight line-clamp-2 group-hover:text-emerald-400 transition-colors duration-300">
                                    {{ $gallery->title }}
                                </h3>
                                <p class="text-slate-400 text-xs mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-200 tracking-wide line-clamp-1">
                                    {{ $gallery->description ?? 'Klik untuk melihat detail' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-20">
                    {{ $galleries->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-32 rounded-[40px] border border-dashed border-slate-800 bg-slate-900/30 backdrop-blur-sm relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900/50"></div>
                    <div class="relative z-10">
                        <i class="far fa-images text-6xl text-slate-700 mb-6 block animate-float"></i>
                        <h3 class="text-xl font-bold text-white uppercase tracking-widest mb-2">Galeri Kosong</h3>
                        <p class="text-slate-500 text-sm mb-8">Tidak ada item galeri yang ditemukan untuk filter ini.</p>
                        <a href="{{ route('public.gallery') }}" wire:navigate class="inline-flex items-center px-8 py-3 rounded-xl bg-slate-800 text-white font-bold uppercase tracking-widest text-xs hover:bg-emerald-600 transition-all shadow-lg hover:shadow-emerald-500/25">
                            Reset Filter
                        </a>
                    </div>
                </div>
            @endif

            {{-- Lightbox --}}
            {{-- Lightbox --}}
            <template x-teleport="body">
                <div x-show="lightboxOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 backdrop-blur-none"
                     x-transition:enter-end="opacity-100 backdrop-blur-xl"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 backdrop-blur-xl"
                     x-transition:leave-end="opacity-0 backdrop-blur-none"
                     class="fixed inset-0 z-[100] bg-slate-950/90 flex flex-col items-center justify-center p-4 md:p-10 select-none"
                     @touchstart="touchStartX = $event.changedTouches[0].screenX"
                     @touchend="touchEndX = $event.changedTouches[0].screenX; handleSwipe()">
                    
                    {{-- Close Button --}}
                    <button @click="lightboxOpen = false" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-20 backdrop-blur-md border border-white/10">
                        <i class="fas fa-times text-xl"></i>
                    </button>

                    {{-- Prev Button (Desktop) --}}
                    <button @click.stop="prev()" class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white items-center justify-center transition-all z-20 backdrop-blur-md border border-white/10 hover:-translate-x-1">
                        <i class="fas fa-chevron-left text-xl"></i>
                    </button>

                    {{-- Next Button (Desktop) --}}
                    <button @click.stop="next()" class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white items-center justify-center transition-all z-20 backdrop-blur-md border border-white/10 hover:translate-x-1">
                        <i class="fas fa-chevron-right text-xl"></i>
                    </button>

                    <div class="relative w-full max-w-6xl max-h-full flex flex-col items-center justify-center" @click.away="lightboxOpen = false">
                        <template x-if="activeItem.type === 'image'">
                            <img :src="activeItem.source" :alt="activeItem.title" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl shadow-black/50">
                        </template>
                        <template x-if="activeItem.type === 'video'">
                            <div class="w-full aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10">
                                <video :src="activeItem.source" controls class="w-full h-full" autoplay></video>
                            </div>
                        </template>
                        <template x-if="activeItem.type === 'youtube'">
                            <div class="w-full aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10">
                                <iframe :src="activeItem.source" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection
