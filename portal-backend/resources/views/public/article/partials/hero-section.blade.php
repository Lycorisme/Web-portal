{{-- Article Hero Section (Full Thumbnail Display) --}}
<div class="relative w-full -mt-16 sm:-mt-20">
    
    {{-- Back Button --}}
    <button onclick="history.back()" 
            class="absolute top-4 left-4 sm:left-8 z-30 flex items-center gap-2 group/btn">
        <div class="w-10 h-10 flex items-center justify-center bg-black/40 backdrop-blur-md border border-white/10 rounded-full group-hover/btn:bg-emerald-500 group-hover/btn:border-emerald-500 transition-all duration-300">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </div>
        <span class="text-white/80 font-medium text-sm group-hover/btn:text-white transition-colors opacity-0 group-hover/btn:opacity-100 -translate-x-2 group-hover/btn:translate-x-0 transition-all duration-300">
            Kembali
        </span>
    </button>

    {{-- Thumbnail Container --}}
    @if($article->image_url)
        <div class="relative w-full h-full">
            {{-- Full Image (Cover to ensure consistency) --}}
            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" 
                 class="w-full h-auto">
            
            {{-- Bottom Fade Gradient (Stronger & Taller) --}}
            <div class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t from-[#020617] via-[#020617]/80 to-transparent pointer-events-none"></div>
            
            {{-- Content Overlay (Padded to 30vh to ensure title sits above overlap) --}}
            <div class="absolute inset-x-0 bottom-0 z-10 w-full" style="padding-bottom: 30vh;">
                <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 text-center">
                    
                    {{-- Meta Badge --}}
                    <div class="flex items-center justify-center gap-3 sm:gap-4 flex-wrap mb-4 sm:mb-6">
                        <span class="px-4 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-md text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-widest shadow-lg shadow-emerald-500/10">
                            {{ $article->categoryRelation?->name ?? 'Umum' }}
                        </span>
                        <span class="flex items-center gap-2 text-slate-300 text-xs sm:text-sm font-medium bg-black/30 backdrop-blur-sm px-3 py-1 rounded-full border border-white/5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight drop-shadow-2xl break-words max-w-full mb-4 sm:mb-6"
                        style="text-shadow: 0 4px 20px rgba(0,0,0,0.8);">
                        {{ $article->title }}
                    </h1>

                    {{-- Author Chip --}}
                    @if($article->author)
                        <div class="inline-flex items-center gap-3 sm:gap-4 p-2 pr-5 bg-white/10 backdrop-blur-md border border-white/10 rounded-full hover:bg-white/15 transition-all duration-300 cursor-default group/author mx-auto">
                            @if($article->author->avatar_url)
                                <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" 
                                     class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-white/10 group-hover/author:ring-emerald-500/50 transition-all">
                            @else
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-white/10">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="text-left flex flex-col justify-center h-full">
                                <span class="text-white text-xs sm:text-sm font-bold leading-none mb-1 block group-hover/author:text-emerald-300 transition-colors">
                                    {{ $article->author->name }}
                                </span>
                                <span class="text-slate-400 text-[10px] sm:text-xs leading-none block">
                                    {{ $article->author->role ?? 'Penulis' }}
                                </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @else
        {{-- Fallback: No Image --}}
        <div class="relative w-full py-16 sm:py-24 bg-gradient-to-b from-slate-900 to-slate-950">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
                <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px]"></div>
            </div>
            
            <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 text-center">
                <div class="flex items-center justify-center gap-3 sm:gap-4 flex-wrap mb-4 sm:mb-6">
                    <span class="px-4 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-widest">
                        {{ $article->categoryRelation?->name ?? 'Umum' }}
                    </span>
                    <span class="flex items-center gap-2 text-slate-400 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight break-words max-w-full mb-4 sm:mb-6">
                    {{ $article->title }}
                </h1>
                @if($article->author)
                    <div class="inline-flex items-center gap-3 p-2 pr-5 bg-white/5 border border-white/10 rounded-full mx-auto">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs">
                            {{ substr($article->author->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <span class="text-white text-xs sm:text-sm font-bold block">{{ $article->author->name }}</span>
                            <span class="text-slate-500 text-[10px] sm:text-xs block">{{ $article->author->role ?? 'Penulis' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
