{{-- Article Hero Section --}}
<section class="relative w-full min-h-[70vh] lg:min-h-[85vh] flex items-end overflow-hidden bg-slate-950">
    
    {{-- Background Image & Overlays --}}
    <div class="absolute inset-0 z-0">
        @if($article->image_url)
            <img src="{{ $article->image_url }}" 
                 alt="{{ $article->title }}" 
                 class="w-full h-full object-cover object-center scale-105 animate-[pulse_10s_ease-in-out_infinite]">
            
            {{-- Modern Multi-layer Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-transparent to-transparent"></div>
        @else
            {{-- Abstract Fallback Background --}}
            <div class="absolute inset-0 bg-slate-900">
                <div class="absolute top-0 -left-4 w-72 h-72 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute top-0 -right-4 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            </div>
        @endif
    </div>



    {{-- Main Content --}}
    <div class="relative z-20 w-full pb-20 lg:pb-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="max-w-4xl space-y-6">
                
                {{-- Metadata Badge --}}
                <div class="flex flex-wrap items-center gap-4">
                    <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded">
                        {{ $article->categoryRelation?->name ?? 'Artikel' }}
                    </span>
                    <div class="flex items-center gap-2 text-slate-300 text-sm font-medium">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                    </div>
                </div>

                {{-- Headline --}}
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-[1.1] tracking-tight">
                    {{ $article->title }}
                </h1>

                {{-- Author Info --}}
                @if($article->author)
                    <div class="flex items-center gap-4 pt-4">
                        <div class="relative">
                            @if(isset($article->author->profile_photo_url))
                                <img src="{{ $article->author->profile_photo_url }}" class="w-12 h-12 rounded-full border-2 border-emerald-500/50 object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-slate-950 rounded-full"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Penulis</span>
                            <span class="text-white font-semibold text-lg hover:text-emerald-400 transition-colors cursor-default">
                                {{ $article->author->name }}
                            </span>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Decorative Bottom Bar --}}
    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
</section>