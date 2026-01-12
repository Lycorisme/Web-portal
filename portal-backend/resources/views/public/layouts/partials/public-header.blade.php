{{-- Public Layout Header --}}
@php
    $siteAddress = \App\Models\SiteSetting::get('site_address', '');
    $sitePhone = \App\Models\SiteSetting::get('site_phone', '');
    $siteEmail = \App\Models\SiteSetting::get('site_email', '');
    $facebookUrl = \App\Models\SiteSetting::get('facebook_url', '');
    $instagramUrl = \App\Models\SiteSetting::get('instagram_url', '');
    $twitterUrl = \App\Models\SiteSetting::get('twitter_url', '');
    $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', '');
    $siteName = \App\Models\SiteSetting::get('site_name', 'BTIKP');
    $siteLogo = \App\Models\SiteSetting::get('logo_url');
    if (empty($siteLogo)) {
        $siteLogo = \App\Models\SiteSetting::get('site_logo', '');
    }
    // Fallback if both are empty? Maybe not needed if logic handles empty checks.

    $siteTagline = \App\Models\SiteSetting::get('site_tagline', 'Portal Berita');
@endphp

<header x-data="{ mobileMenuOpen: false, searchOpen: false, scrolled: false }"
        x-init="$watch('mobileMenuOpen', value => value ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')); $watch('searchOpen', value => value ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden'))"
        @scroll.window="scrolled = (window.pageYOffset > 10)"
        class="fixed top-0 w-full z-50 transition-all duration-300"
        :class="{ 'bg-slate-900/80 backdrop-blur-md shadow-lg shadow-black/5 border-b border-white/5 py-3': scrolled, 'bg-transparent py-5': !scrolled }">
    
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                @if(!empty($siteLogo))
                    <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="h-10 w-auto group-hover:scale-105 transition-transform duration-300">
                @endif

                <div class="flex flex-col">
                    <span class="font-display font-bold text-xl md:text-2xl text-white leading-none tracking-tight group-hover:text-emerald-400 transition-colors">
                        {{ $siteName }}
                    </span>
                    <span class="text-[0.6rem] md:text-xs font-bold text-slate-400 uppercase tracking-[0.2em] group-hover:text-slate-300 transition-colors">
                        {{ $siteTagline }}
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-1 p-1 bg-slate-950/30 backdrop-blur-sm border border-white/5 rounded-full">
                <a href="{{ route('public.home') }}" 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 relative overflow-hidden group {{ request()->routeIs('public.home') ? 'text-white bg-white/10 shadow-inner' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                   Beranda
                </a>
                <a href="{{ route('public.articles') }}" 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->routeIs('public.articles') ? 'text-white bg-white/10 shadow-inner' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                   Artikel
                </a>
                <a href="{{ route('public.gallery') }}" 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->routeIs('public.gallery') ? 'text-white bg-white/10 shadow-inner' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                   Galeri
                </a>
            </nav>

            {{-- Right Actions --}}
            <div class="flex items-center gap-4">
                {{-- Search Toggle --}}
                <button @click="searchOpen = !searchOpen" 
                        class="w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:text-white hover:bg-white/10 transition-all border border-transparent hover:border-white/10">
                    <i class="fas fa-search"></i>
                </button>

                {{-- Auth Buttons --}}
                <div class="hidden md:flex items-center">
                    @auth
                        @if(auth()->user()->canAccessDashboard())
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5 border border-emerald-400/20">
                                Dashboard
                            </a>
                        @else
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-slate-300">{{ auth()->user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-rose-500/80 transition-all">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="group relative px-6 py-2.5 rounded-xl overflow-hidden bg-slate-800 border border-slate-700 text-slate-300 font-bold text-xs uppercase tracking-wider hover:text-white hover:border-slate-500 transition-all">
                            <span class="relative z-10 flex items-center gap-2">
                                Masuk <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-blue-600/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-slate-800 text-slate-200">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu (Fullscreen) --}}
    <div x-show="mobileMenuOpen"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-2xl"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-2xl"
         x-transition:leave-end="opacity-0 backdrop-blur-none"
         class="fixed inset-0 z-[100] md:hidden bg-slate-950/90 backdrop-blur-2xl flex flex-col justify-center items-center">
        
        {{-- Close Button --}}
        <button @click="mobileMenuOpen = false" class="absolute top-8 right-8 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all border border-white/10">
            <i class="fas fa-times text-xl"></i>
        </button>

        {{-- Mobile Nav Items --}}

        <div class="flex flex-col items-center gap-8 w-full max-w-sm px-6">
            {{-- Logo in Mobile Menu --}}
            @if(!empty($siteLogo))
                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="h-16 w-auto mb-2">
            @else
                 <span class="text-3xl font-bold font-display text-white mb-2">{{ $siteName }}</span>
            @endif

            <a href="{{ route('public.home') }}" 
               class="text-3xl font-display font-bold transition-colors tracking-tight {{ request()->routeIs('public.home') ? 'text-emerald-400' : 'text-white hover:text-emerald-400' }}">
               Beranda
            </a>
            <a href="{{ route('public.articles') }}" 
               class="text-3xl font-display font-bold transition-colors tracking-tight {{ request()->routeIs('public.articles*') ? 'text-emerald-400' : 'text-white hover:text-emerald-400' }}">
               Artikel
            </a>
            <a href="{{ route('public.gallery') }}" 
               class="text-3xl font-display font-bold transition-colors tracking-tight {{ request()->routeIs('public.gallery') ? 'text-emerald-400' : 'text-white hover:text-emerald-400' }}">
               Galeri
            </a>
            
            <div class="w-20 h-px bg-slate-700 my-4"></div>
            
            @auth
                 <div class="flex flex-col items-center gap-4 w-full">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-emerald-500 font-bold text-xl border border-emerald-500/20 shadow-lg shadow-emerald-500/10">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <div class="font-bold text-white text-lg">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-slate-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                     @if(auth()->user()->canAccessDashboard())
                        <a href="{{ route('dashboard') }}" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 rounded-2xl text-center text-white font-bold uppercase tracking-widest text-sm shadow-xl shadow-emerald-600/20 transition-all">
                            Dashboard
                        </a>
                    @endif
                     <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-slate-800 hover:bg-slate-700 rounded-2xl text-center text-rose-400 font-bold uppercase tracking-widest text-sm border border-slate-700 transition-all">
                            Keluar
                        </button>
                    </form>
                 </div>
            @else
                <a href="{{ route('login') }}" class="w-full py-4 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 rounded-2xl text-center text-white font-bold uppercase tracking-widest text-sm shadow-xl shadow-emerald-600/20 transition-all">
                    Masuk / Daftar
                </a>
            @endauth
        </div>
    </div>

    {{-- Search Overlay (Fullscreen) --}}
    <div x-show="searchOpen"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-2xl"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-2xl"
         x-transition:leave-end="opacity-0 backdrop-blur-none"
         class="fixed inset-0 z-[100] bg-slate-950/90 backdrop-blur-2xl flex flex-col justify-center items-center p-6">
        
        {{-- Close Button --}}
        <button @click="searchOpen = false" class="absolute top-8 right-8 w-12 h-12 rounded-full bg-slate-800/50 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition-all border border-white/5">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="w-full max-w-3xl text-center">
            {{-- Logo in Search Modal --}}
            @if(!empty($siteLogo))
                <div class="flex justify-center mb-6">
                    <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="h-16 w-auto">
                </div>
            @endif
            <h3 class="text-white font-display font-medium text-2xl mb-8 tracking-tight">Apa yang ingin Anda cari?</h3>
            
            <form action="{{ route('public.articles') }}" method="GET" class="relative group">
                <input type="text" name="q" placeholder="Ketik kata kunci..." 
                       x-trap="searchOpen"
                       class="w-full bg-transparent border-b-2 border-slate-700 text-white text-3xl md:text-5xl font-display font-bold py-4 px-2 focus:outline-none focus:border-emerald-500 transition-all placeholder:text-slate-700 text-center">
                
                <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 w-16 h-16 text-slate-600 hover:text-emerald-500 transition-colors flex items-center justify-center">
                    <i class="fas fa-arrow-right text-2xl"></i>
                </button>
            </form>
            
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <span class="text-slate-500 text-sm font-semibold uppercase tracking-widest">Populer:</span>
                <a href="{{ route('public.articles', ['tag' => 'teknologi']) }}" class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors underline decoration-emerald-500/30 font-display">Teknologi</a>
                <a href="{{ route('public.articles', ['tag' => 'pendidikan']) }}" class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors underline decoration-emerald-500/30 font-display">Pendidikan</a>
                <a href="{{ route('public.articles', ['tag' => 'digital']) }}" class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors underline decoration-emerald-500/30 font-display">Digital</a>
            </div>
        </div>
    </div>

</header>
{{-- No spacer needed --}}
