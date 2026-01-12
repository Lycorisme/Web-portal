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
        x-init="$watch('mobileMenuOpen', value => { if(value) document.body.classList.add('overflow-hidden'); else setTimeout(() => document.body.classList.remove('overflow-hidden'), 500); }); 
                $watch('searchOpen', value => { if(value) document.body.classList.add('overflow-hidden'); else setTimeout(() => document.body.classList.remove('overflow-hidden'), 500); })"
        @scroll.window="scrolled = (window.pageYOffset > 10)"
        class="fixed top-0 w-full z-50 transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]"
        :class="{ 'bg-slate-900/80 backdrop-blur-md shadow-lg shadow-black/5 py-4': scrolled, 'bg-transparent py-6': !scrolled }">
    
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ route('public.home') }}" wire:navigate class="flex items-center gap-3 group">
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
            {{-- Desktop Navigation with Sliding Pill --}}
            @persist('desktop-navigation')
            <nav x-data="{
                activeRect: { width: 0, left: 0 },
                currentPath: window.location.pathname,
                init() {
                    // Set initial active state based on current URL
                    this.currentPath = window.location.pathname;
                    this.updateActive();
                    
                    // Listen for Livewire navigation to update state
                    document.addEventListener('livewire:navigated', () => {
                         this.currentPath = window.location.pathname;
                         this.updateActive();
                    });
                },
                isActive(route) {
                    if (route === '{{ parse_url(route('public.home'), PHP_URL_PATH) }}') {
                        return this.currentPath === route;
                    }
                    return this.currentPath.startsWith(route);
                },
                updateActive() {
                    this.$nextTick(() => {
                        // Find the link that corresponds to the current path
                        const links = this.$refs.nav.querySelectorAll('a');
                        let activeEl = null;

                        // Check each link
                         const homePath = '{{ parse_url(route('public.home'), PHP_URL_PATH) }}';
                         const articlesPath = '{{ parse_url(route('public.articles'), PHP_URL_PATH) }}';
                         const galleryPath = '{{ parse_url(route('public.gallery'), PHP_URL_PATH) }}';

                        if (this.currentPath === homePath) {
                            activeEl = links[0];
                        } else if (this.currentPath.startsWith(articlesPath)) {
                            activeEl = links[1];
                        } else if (this.currentPath.startsWith(galleryPath)) {
                            activeEl = links[2];
                        }

                        if (activeEl) {
                            this.activeRect.width = activeEl.offsetWidth;
                            this.activeRect.left = activeEl.offsetLeft;
                        } else {
                            this.activeRect.width = 0;
                        }
                    });
                }
            }" 
            x-ref="nav"
            @resize.window="updateActive()"
            class="hidden lg:flex items-center gap-1 p-1 bg-slate-950/30 backdrop-blur-sm border border-white/5 rounded-full relative">
                
                {{-- Sliding Pill --}}
                <div class="absolute top-1 bottom-1 rounded-full bg-emerald-500/20 shadow-inner border border-emerald-500/30 transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1)"
                     :style="`left: ${activeRect.left}px; width: ${activeRect.width}px`"
                     style="display: none;" 
                     x-show="activeRect.width > 0"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"></div>

                <a href="{{ route('public.home') }}" wire:navigate 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-colors duration-300 relative z-10"
                   :class="isActive('{{ parse_url(route('public.home'), PHP_URL_PATH) }}') ? 'text-emerald-400' : 'text-slate-400 hover:text-white'">
                   Beranda
                </a>
                <a href="{{ route('public.articles') }}" wire:navigate 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-colors duration-300 relative z-10"
                   :class="isActive('{{ parse_url(route('public.articles'), PHP_URL_PATH) }}') ? 'text-emerald-400' : 'text-slate-400 hover:text-white'">
                   Artikel
                </a>
                <a href="{{ route('public.gallery') }}" wire:navigate 
                   class="px-5 py-2 rounded-full text-sm font-bold transition-colors duration-300 relative z-10"
                   :class="isActive('{{ parse_url(route('public.gallery'), PHP_URL_PATH) }}') ? 'text-emerald-400' : 'text-slate-400 hover:text-white'">
                   Galeri
                </a>
            </nav>
            @endpersist

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
                                Masuk / Daftar <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
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
    {{-- Mobile Menu (Fullscreen) --}}
    <template x-teleport="body">
        <div x-show="mobileMenuOpen"
             style="display: none;"
             x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
             x-transition:enter-start="-translate-y-full opacity-50"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="-translate-y-full opacity-50"
             class="fixed inset-0 z-[9999] bg-slate-950/98 backdrop-blur-xl flex flex-col justify-center items-center">
            
            {{-- Close Button --}}
            <button @click="mobileMenuOpen = false" 
                    class="absolute top-8 right-8 w-12 h-12 rounded-full bg-white/5 hover:bg-white/10 text-white flex items-center justify-center transition-all border border-white/10 group z-50 hover:scale-110 active:scale-95">
                <i class="fas fa-times text-xl group-hover:rotate-90 transition-transform duration-300"></i>
            </button>

            {{-- Mobile Nav Items --}}
            <div class="flex flex-col items-center w-full max-w-sm px-6 h-full overflow-y-auto py-24 hide-scrollbar">
                
                {{-- Logo in Mobile Menu --}}
                <div class="mb-8"
                     x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-out duration-500 delay-100"
                     x-transition:enter-start="opacity-0 -translate-y-8 scale-90"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    @if(!empty($siteLogo))
                        <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="h-20 w-auto">
                    @else
                         <span class="text-4xl font-bold font-display text-white">{{ $siteName }}</span>
                    @endif
                </div>

                <div class="space-y-6 flex flex-col items-center w-full">
                    @foreach([
                        ['label' => 'Beranda', 'route' => 'public.home'],
                        ['label' => 'Artikel', 'route' => 'public.articles'],
                        ['label' => 'Galeri', 'route' => 'public.gallery']
                    ] as $index => $item)
                        <a href="{{ route($item['route']) }}" wire:navigate 
                           class="text-4xl font-display font-bold transition-all tracking-tight {{ request()->routeIs($item['route'].'*') ? 'text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 scale-105' : 'text-slate-400 hover:text-white hover:scale-105' }}"
                           x-show="mobileMenuOpen"
                           x-transition:enter="transition ease-out duration-500"
                           x-transition:enter-start="opacity-0 translate-y-8"
                           x-transition:enter-end="opacity-100 translate-y-0"
                           style="transition-delay: {{ ($index + 2) * 100 }}ms">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
                
                <div class="w-20 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent my-10"
                     x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-out duration-700 delay-500"
                     x-transition:enter-start="opacity-0 scale-x-0"
                     x-transition:enter-end="opacity-100 scale-x-100"></div>
                
                <div x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-out duration-500 delay-[600ms]"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="w-full">
                    @auth
                         <div class="flex flex-col items-center gap-6 w-full">
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                                <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-emerald-500 font-bold text-xl border border-emerald-500/20 shadow-lg shadow-emerald-500/10">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-white text-lg">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-slate-400">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                             @if(auth()->user()->canAccessDashboard())
                                <a href="{{ route('dashboard') }}" wire:navigate class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 rounded-2xl text-center text-white font-bold uppercase tracking-widest text-sm shadow-xl shadow-emerald-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    Dashboard
                                </a>
                            @endif
                             <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-slate-800 hover:bg-slate-700 rounded-2xl text-center text-rose-400 font-bold uppercase tracking-widest text-sm border border-slate-700 transition-all hover:border-rose-500/30">
                                    Keluar
                                </button>
                            </form>
                         </div>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-4 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 rounded-2xl text-center text-white font-bold uppercase tracking-widest text-sm shadow-xl shadow-emerald-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Masuk / Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </template>

    {{-- Search Overlay (Fullscreen) --}}
    <template x-teleport="body">
        <div x-show="searchOpen"
             style="display: none;"
             x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
             x-transition:enter-start="-translate-y-full opacity-50"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="-translate-y-full opacity-50"
             class="fixed inset-0 z-[9999] bg-slate-950/98 backdrop-blur-xl flex flex-col justify-center items-center p-6">
            
            {{-- Close Button --}}
            <button @click="searchOpen = false" 
                    class="absolute top-8 right-8 w-12 h-12 rounded-full bg-slate-800/50 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition-all border border-white/5 group hover:scale-110 active:scale-95">
                <i class="fas fa-times text-xl group-hover:rotate-90 transition-transform duration-300"></i>
            </button>

            <div class="w-full max-w-3xl text-center relative z-10">
                {{-- Logo in Search Modal --}}
                @if(!empty($siteLogo))
                    <div class="flex justify-center mb-8"
                         x-show="searchOpen"
                         x-transition:enter="transition ease-out duration-700 delay-100"
                         x-transition:enter-start="opacity-0 -translate-y-8 scale-90"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                        <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="h-20 w-auto">
                    </div>
                @endif
                <h3 class="text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400 font-display font-medium text-2xl lg:text-3xl mb-10 tracking-tight"
                    x-show="searchOpen"
                    x-transition:enter="transition ease-out duration-700 delay-200"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    Apa yang ingin Anda cari?
                </h3>
                
                <form action="{{ route('public.articles') }}" method="GET" class="relative group"
                      x-show="searchOpen"
                      x-transition:enter="transition ease-out duration-700 delay-300"
                      x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                      x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <input type="text" name="q" placeholder="Ketik kata kunci..." 
                           x-trap.noscroll="searchOpen"
                           class="w-full bg-transparent border-b-2 border-slate-700/50 hover:border-slate-500 text-white text-3xl md:text-5xl lg:text-6xl font-display font-bold py-6 px-4 focus:outline-none focus:border-emerald-500 transition-all placeholder:text-slate-700 text-center">
                    
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 w-16 h-16 text-slate-600 hover:text-emerald-500 transition-colors flex items-center justify-center group-focus-within:text-emerald-500">
                        <i class="fas fa-arrow-right text-2xl group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
                
                <div class="mt-12 flex flex-wrap justify-center gap-3"
                     x-show="searchOpen"
                     x-transition:enter="transition ease-out duration-700 delay-500"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <span class="text-slate-500 text-sm font-semibold uppercase tracking-widest">Populer:</span>
                    <a href="{{ route('public.articles', ['tag' => 'teknologi']) }}" wire:navigate class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors underline decoration-emerald-500/30 font-display">Teknologi</a>
                    <a href="{{ route('public.articles', ['tag' => 'pendidikan']) }}" wire:navigate class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors underline decoration-emerald-500/30 font-display">Pendidikan</a>
                    <a href="{{ route('public.articles', ['tag' => 'digital']) }}" wire:navigate class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors underline decoration-emerald-500/30 font-display">Digital</a>
                </div>
            </div>
        </div>
    </template>

</header>
{{-- No spacer needed --}}
