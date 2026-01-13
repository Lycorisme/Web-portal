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
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#020617]/80 to-transparent opacity-60"></div>
            
            {{-- Content Overlay (Bottom LEFT) --}}
            <div class="absolute inset-0 z-20 w-full flex flex-col justify-end pb-16 sm:pb-20 lg:pb-24 px-4 sm:px-6">
                <div class="w-full max-w-5xl mx-auto">
                    
                    {{-- Meta Badge --}}
                    <div class="flex items-center gap-3 sm:gap-4 flex-wrap mb-6">
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-md text-[10px] sm:text-xs font-bold rounded-lg uppercase tracking-widest shadow-lg shadow-emerald-500/10 hover:bg-emerald-500/30 transition-colors">
                            {{ $article->categoryRelation?->name ?? 'Umum' }}
                        </span>
                        <span class="flex items-center gap-2 text-slate-300 text-xs sm:text-sm font-medium">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 opacity-70"></i>
                            {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight tracking-tight drop-shadow-lg break-words max-w-4xl mb-6">
                        {{ $article->title }}
                    </h1>

                    {{-- Author --}}
                    @if($article->author)
                        <div class="flex items-center gap-3">
                            @if($article->author->avatar_url)
                                <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" 
                                     class="w-10 h-10 rounded-full object-cover ring-2 ring-emerald-500/50">
                            @else
                                <div class="w-10 h-10 rounded-full bg-theme-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-emerald-500/50">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="text-left flex flex-col justify-center">
                                <span class="text-white text-sm font-bold leading-none mb-1">
                                    {{ $article->author->name }}
                                </span>
                                <span class="text-emerald-400 text-xs font-medium leading-none">
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
