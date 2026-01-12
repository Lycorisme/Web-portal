{{-- Article Hero Section --}}
<header class="relative w-full max-w-full overflow-hidden">
    {{-- Floating Back Button --}}
    <button onclick="history.back()" 
            class="absolute top-24 left-4 sm:top-28 sm:left-6 z-20 inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-slate-900/70 backdrop-blur-md rounded-xl border border-white/10 hover:border-emerald-500/50 text-white/80 hover:text-emerald-400 transition-all group shadow-lg">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="text-xs sm:text-sm font-semibold">Kembali</span>
    </button>

    @if($article->image_url)
        {{-- With Featured Image --}}
        <div class="relative min-h-[50vh] sm:min-h-[60vh] lg:min-h-[70vh] flex items-end">

            {{-- Background --}}
            <div class="absolute inset-0 overflow-hidden">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/80 to-[#020617]/40"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#020617]/60 to-transparent"></div>
            </div>
            
            {{-- Content --}}
            <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10 lg:py-16">
                <div class="max-w-3xl space-y-4 sm:space-y-5">
                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <span class="px-3 sm:px-4 py-1 sm:py-1.5 bg-emerald-500 text-white text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-wider shadow-lg shadow-emerald-500/30">
                            {{ $article->categoryRelation?->name ?? 'Umum' }}
                        </span>
                        <span class="text-slate-300 text-xs sm:text-sm">
                            {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                    
                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight break-words">
                        {{ $article->title }}
                    </h1>
                    
                    {{-- Author --}}
                    @if($article->author)
                        <div class="flex items-center gap-3 sm:gap-4 pt-2">
                            @if($article->author->avatar_url)
                                <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" 
                                     class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover ring-2 ring-white/20 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-white text-sm sm:text-base truncate">{{ $article->author->name }}</p>
                                <p class="text-xs sm:text-sm text-slate-400">{{ $article->author->role ?? 'Penulis' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        {{-- Without Featured Image --}}
        <div class="pt-32 md:pt-40 pb-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6">
                <div class="max-w-3xl space-y-4 sm:space-y-5">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <span class="px-3 sm:px-4 py-1 sm:py-1.5 bg-emerald-500/10 text-emerald-400 text-[10px] sm:text-xs font-bold rounded-full uppercase tracking-wider border border-emerald-500/20">
                            {{ $article->categoryRelation?->name ?? 'Umum' }}
                        </span>
                        <span class="text-slate-400 text-xs sm:text-sm">
                            {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight break-words">
                        {{ $article->title }}
                    </h1>
                    @if($article->author)
                        <div class="flex items-center gap-3 sm:gap-4 pt-2">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0">
                                {{ substr($article->author->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-white text-sm sm:text-base truncate">{{ $article->author->name }}</p>
                                <p class="text-xs sm:text-sm text-slate-400">{{ $article->author->role ?? 'Penulis' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</header>
