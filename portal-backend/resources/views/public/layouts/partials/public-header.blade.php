{{-- Public Layout Header --}}
@php
    $siteAddress = \App\Models\SiteSetting::get('site_address', '');
    $sitePhone = \App\Models\SiteSetting::get('site_phone', '');
    $siteEmail = \App\Models\SiteSetting::get('site_email', '');
    $facebookUrl = \App\Models\SiteSetting::get('facebook_url', '');
    $instagramUrl = \App\Models\SiteSetting::get('instagram_url', '');
    $twitterUrl = \App\Models\SiteSetting::get('twitter_url', '');
    $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', '');
@endphp

<header x-data="{ mobileMenuOpen: false, searchOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 10)"
        class="fixed top-0 w-full z-50 transition-all duration-300"
        :class="{ 'bg-slate-900/80 backdrop-blur-md shadow-lg shadow-black/5 border-b border-white/5 py-3': scrolled, 'bg-transparent py-5': !scrolled }">
    
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                @if(!empty($siteSettings['logo_url']))
                    <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}" class="h-10 w-auto group-hover:scale-105 transition-transform duration-300">
                @endif
                <div class="flex flex-col">
                    <span class="font-display font-bold text-xl md:text-2xl text-white leading-none tracking-tight group-hover:text-emerald-400 transition-colors">
                        {{ $siteSettings['site_name'] ?? 'BTIKP' }}
                    </span>
                    <span class="text-[0.6rem] md:text-xs font-bold text-slate-400 uppercase tracking-[0.2em] group-hover:text-slate-300 transition-colors">
                        Portal Berita
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
                <div class="relative">
                    <button @click="searchOpen = !searchOpen" 
                            class="w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:text-white hover:bg-white/10 transition-all border border-transparent hover:border-white/10">
                        <i class="fas fa-search"></i>
                    </button>
                    {{-- Search Dropdown --}}
                    <div x-show="searchOpen" 
                         @click.away="searchOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                         class="absolute right-0 top-full mt-2 w-72 bg-slate-900 border border-slate-700/50 rounded-2xl p-4 shadow-2xl overflow-hidden glass z-50">
                         <form action="{{ route('public.articles') }}" method="GET" class="relative">
                            <input type="text" name="search" placeholder="Cari berita..." 
                                   class="w-full bg-slate-950 border border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3 pl-10 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all placeholder:text-slate-600">
                            <i class="fas fa-search absolute left-3.5 top-3.5 text-slate-500"></i>
                         </form>
                    </div>
                </div>

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

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden absolute top-full left-0 w-full bg-slate-900/95 backdrop-blur-lg border-b border-slate-800 shadow-2xl p-6 flex flex-col gap-4">
        
        <a href="{{ route('public.home') }}" class="text-lg font-bold text-slate-300 hover:text-emerald-400 transition-colors">Beranda</a>
        <a href="{{ route('public.articles') }}" class="text-lg font-bold text-slate-300 hover:text-emerald-400 transition-colors">Artikel</a>
        <a href="{{ route('public.gallery') }}" class="text-lg font-bold text-slate-300 hover:text-emerald-400 transition-colors">Galeri</a>
        
        <hr class="border-slate-800 my-2">
        
        @auth
             <div class="flex flex-col gap-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Akun</span>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-emerald-500 font-bold text-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                 @if(auth()->user()->canAccessDashboard())
                    <a href="{{ route('dashboard') }}" class="mt-2 block w-full py-3 bg-emerald-600 rounded-xl text-center text-white font-bold uppercase tracking-widest text-xs">
                        Ke Dashboard
                    </a>
                @endif
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mt-2 w-full py-3 bg-slate-800 rounded-xl text-center text-rose-400 font-bold uppercase tracking-widest text-xs">
                        Keluar
                    </button>
                </form>
             </div>
        @else
            <a href="{{ route('login') }}" class="block w-full py-3 bg-emerald-600 rounded-xl text-center text-white font-bold uppercase tracking-widest text-xs">
                Masuk / Daftar
            </a>
        @endauth
    </div>
</header>
<div class="h-24 md:h-28 hidden"></div> {{-- Spacer if needed, but we are using fixed header --}}
