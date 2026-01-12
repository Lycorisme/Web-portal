{{-- Article Hero Section (Full Thumbnail Display) --}}
<div class="relative w-full -mt-16 sm:-mt-20 h-[60vh] sm:h-[75vh] lg:h-[85vh] group/hero overflow-hidden">
    
    {{-- Back Button --}}
    <button onclick="history.back()" 
            class="absolute top-24 left-4 sm:left-8 z-30 flex items-center gap-2 group/btn">
        <div class="w-10 h-10 flex items-center justify-center bg-black/20 backdrop-blur-md border border-white/10 rounded-full group-hover/btn:bg-emerald-500 group-hover/btn:border-emerald-500 transition-all duration-300">
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
            {{-- Image with Focus on Center --}}
            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" 
                 class="w-full h-full object-cover object-center transform group-hover/hero:scale-105 transition-transform duration-1000 ease-out">
            
            {{-- Overlay Gradients for Readability --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-[#020617] opacity-80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/50 to-transparent"></div>
            
            {{-- Content Overlay (Centered) --}}
            <div class="absolute inset-0 z-20 w-full flex flex-col items-center justify-center px-4 sm:px-6">
                <div class="w-full max-w-4xl text-center">
                    
                    {{-- Meta Badge --}}
                    <div class="flex items-center justify-center gap-3 sm:gap-4 flex-wrap mb-6">
                        <span class="px-4 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-md text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-widest shadow-lg shadow-emerald-500/10">
                            {{ $article->categoryRelation?->name ?? 'Umum' }}
                        </span>
                        <span class="flex items-center gap-2 text-slate-300 text-xs sm:text-sm font-medium bg-black/20 backdrop-blur-sm px-3 py-1 rounded-full border border-white/5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight drop-shadow-2xl break-words mb-8"
                        style="text-shadow: 0 4px 30px rgba(0,0,0,0.8);">
                        {{ $article->title }}
                    </h1>

                    {{-- Author Chip --}}
                    @if($article->author)
                        <div class="inline-flex items-center gap-3 p-2 pr-6 bg-white/5 backdrop-blur-md border border-white/10 rounded-full hover:bg-white/10 transition-all duration-300 cursor-default group/author">
                            @if($article->author->avatar_url)
                                <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" 
                                     class="w-10 h-10 rounded-full object-cover ring-2 ring-white/20 group-hover/author:ring-emerald-500/50 transition-all">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-white/20">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="text-left flex flex-col justify-center">
                                <span class="text-white text-sm font-bold leading-none mb-1 group-hover/author:text-emerald-300 transition-colors">
                                    {{ $article->author->name }}
                                </span>
                                <span class="text-slate-400 text-xs leading-none">
                                    {{ $article->author->role ?? 'Penulis' }}
                                </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @else
        {{-- Fallback: No Image (Consistently Sized) --}}
        <div class="relative w-full h-full flex items-center justify-center bg-gradient-to-b from-slate-900 via-slate-900 to-[#020617]">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
                <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px]"></div>
            </div>
            
            <div class="relative z-10 w-full max-w-4xl px-4 sm:px-6 text-center">
                {{-- Same content structure for fallback --}}
                <div class="flex items-center justify-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold rounded-full uppercase tracking-widest">
                        {{ $article->categoryRelation?->name ?? 'Umum' }}
                    </span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight mb-6">
                    {{ $article->title }}
                </h1>
                {{-- Author Fallback --}}
                 @if($article->author)
                    <div class="inline-flex items-center gap-3 p-2 pr-6 bg-white/5 border border-white/10 rounded-full">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                             {{ substr($article->author->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <span class="text-white text-sm font-bold block">{{ $article->author->name }}</span>
                            <span class="text-slate-400 text-xs block">{{ $article->author->role ?? 'Penulis' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
