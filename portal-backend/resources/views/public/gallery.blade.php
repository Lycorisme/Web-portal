@extends('layouts.public-layout')

@section('meta_title', 'Galeri')

@section('content')
    <div class="pt-12 pb-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <h1 class="text-3xl lg:text-5xl font-extrabold text-white mb-4 tracking-tight">Galeri Multimedia</h1>
            <p class="text-slate-400 max-w-2xl text-sm font-bold tracking-widest uppercase">
                Dokumentasi kegiatan dan momen penting dalam bentuk foto dan video.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12" x-data="{ lightboxOpen: false, activeImage: '', activeTitle: '', activeType: '' }">
        
        <!-- Filters -->
        <div class="mb-12 flex flex-wrap gap-4 items-center justify-center lg:justify-start">
            <a href="{{ route('public.gallery') }}" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ !request('album') && !request('type') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:border-emerald-500/50' }}">
                Semua
            </a>
            <a href="{{ route('public.gallery', array_merge(request()->query(), ['type' => 'image'])) }}" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request('type') == 'image' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:border-emerald-500/50' }}">
                Foto
            </a>
            <a href="{{ route('public.gallery', array_merge(request()->query(), ['type' => 'video'])) }}" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request('type') == 'video' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:border-emerald-500/50' }}">
                Video
            </a>

            <!-- Albums Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:border-emerald-500/50 flex items-center">
                    {{ request('album') ? 'Album: ' . request('album') : 'Pilih Album' }}
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="absolute z-20 mt-2 w-56 rounded-xl shadow-xl bg-slate-900 border border-slate-800 py-1 focus:outline-none" style="display: none;">
                    <a href="{{ route('public.gallery', array_diff_key(request()->query(), ['album' => ''])) }}" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white uppercase tracking-wide">
                        Semua Album
                    </a>
                    @foreach($albums as $album)
                        <a href="{{ route('public.gallery', array_merge(request()->query(), ['album' => $album])) }}" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white uppercase tracking-wide">
                            {{ $album }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Grid -->
        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                    <div class="group relative rounded-[24px] overflow-hidden aspect-square bg-slate-900 border border-slate-800 cursor-pointer shadow-lg hover:shadow-emerald-500/10 transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/30"
                         @click="
                            @if($gallery->media_type == 'image')
                                lightboxOpen = true; 
                                activeImage = '{{ $gallery->image_url }}'; 
                                activeTitle = '{{ $gallery->title }}';
                                activeType = 'image';
                            @else
                                lightboxOpen = true; 
                                activeImage = '{{ $gallery->video_url }}'; 
                                activeTitle = '{{ $gallery->title }}';
                                activeType = 'video';
                            @endif
                         ">
                        
                        @if($gallery->media_type == 'video' && $gallery->thumbnail_url)
                             <img src="{{ $gallery->thumbnail_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-100">
                             <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-900/80 backdrop-blur border border-slate-700 flex items-center justify-center text-emerald-500 shadow-xl group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.354 11.836a.25.25 0 00.373.23l6.25-4.25a.25.25 0 000-.419l-6.25-4.25a.25.25 0 00-.373.23v8.459z"></path></svg>
                                </div>
                             </div>
                        @else
                            @if($gallery->image_url)
                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-[10px] font-black uppercase text-slate-700">No Image</span>
                                </div>
                            @endif
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                            <span class="inline-block px-2 py-0.5 rounded bg-emerald-600 w-fit text-white text-[10px] font-black mb-2 uppercase tracking-widest">
                                {{ $gallery->media_type == 'video' ? 'Video' : 'Foto' }}
                            </span>
                            <h3 class="text-white font-bold text-sm leading-tight line-clamp-2">{{ $gallery->title }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $galleries->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-slate-900/50 rounded-[32px] border border-dashed border-slate-800">
                <h3 class="text-lg font-bold text-white uppercase tracking-widest">Galeri Kosong</h3>
                <div class="mt-6">
                    <a href="{{ route('public.gallery') }}" class="inline-flex items-center px-6 py-2 border border-slate-700 shadow-sm text-xs font-bold uppercase tracking-widest rounded-xl text-white hover:bg-slate-800 transition-all">
                        Reset Filter
                    </a>
                </div>
            </div>
        @endif

        <!-- Lightbox Modal -->
        <div x-show="lightboxOpen" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/90 backdrop-blur-md p-4">
            
            <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-slate-400 hover:text-white z-50 transition-colors">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="relative w-full max-w-5xl max-h-[90vh] flex flex-col items-center justify-center p-4">
                <template x-if="activeType === 'image'">
                    <img :src="activeImage" :alt="activeTitle" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl ring-1 ring-slate-800">
                </template>
                <template x-if="activeType === 'video'">
                    <div class="w-full aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl ring-1 ring-slate-800">
                        <video :src="activeImage" controls class="w-full h-full" autoplay></video>
                    </div>
                </template>
                
                <h3 x-text="activeTitle" class="text-white font-bold text-lg mt-6 text-center tracking-tight"></h3>
            </div>
        </div>
    </div>
@endsection
