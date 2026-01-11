<header class="sticky top-0 z-50 w-full transition-all duration-300" 
        x-data="{ scrolled: false, mobileMenu: false }" 
        :class="{ 'glass-nav py-3': scrolled, 'bg-transparent py-5': !scrolled }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <!-- Logo Area -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="{{ route('public.home') }}" class="group flex items-center gap-2.5">
                    @if(isset($siteSettings['logo_url']) && $siteSettings['logo_url'])
                        <div class="relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-lg blur opacity-40 group-hover:opacity-100 transition duration-200"></div>
                            <img class="relative h-11 w-auto" src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] }}">
                        </div>
                    @else
                        <div class="relative w-11 h-11">
                            <div class="absolute inset-0 bg-gradient-to-tr from-cyan-400 via-blue-500 to-purple-600 rounded-xl blur-sm group-hover:blur-md transition-all"></div>
                            <div class="relative w-full h-full bg-slate-900 rounded-xl flex items-center justify-center text-white font-black text-xl border border-white/20">
                                {{ substr($siteSettings['site_name'] ?? 'B', 0, 1) }}
                            </div>
                        </div>
                    @endif
                    <div class="hidden md:block">
                        <h1 class="text-2xl font-black tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-slate-900 via-indigo-800 to-slate-900 group-hover:from-indigo-600 group-hover:via-pink-500 group-hover:to-orange-500 transition-all duration-500">
                            {{ $siteSettings['site_name'] ?? 'PORTAL' }}
                        </h1>
                    </div>
                </a>
            </div>

            <!-- Floating Pill Navigation (Desktop) -->
            <nav class="hidden lg:flex items-center gap-1.5 p-1.5 rounded-full bg-white/40 backdrop-blur-md border border-white/40 shadow-lg shadow-indigo-500/5">
                @foreach([
                    ['label' => 'Beranda', 'route' => 'public.home', 'icon' => 'fa-home'],
                    ['label' => 'Berita', 'route' => 'public.articles', 'icon' => 'fa-newspaper'],
                    ['label' => 'Galeri', 'route' => 'public.gallery', 'icon' => 'fa-images'],
                ] as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="relative px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 overflow-hidden group 
                              {{ request()->routeIs($item['route'].'*') ? 'text-white shadow-md' : 'text-slate-600 hover:text-indigo-600' }}">
                        
                        @if(request()->routeIs($item['route'].'*'))
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                        @else
                            <div class="absolute inset-0 bg-white hover:bg-white/80 transition-colors"></div>
                        @endif
                        
                        <div class="relative flex items-center gap-2 z-10">
                            <i class="fas {{ $item['icon'] }}"></i> {{ $item['label'] }}
                        </div>
                    </a>
                @endforeach
            </nav>

            <!-- Actions Area -->
            <div class="flex items-center gap-4">
                <!-- Search Button -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-indigo-500 rounded-full blur opacity-0 group-hover:opacity-75 transition duration-500"></div>
                    <button class="relative w-11 h-11 rounded-full bg-white flex items-center justify-center text-slate-500 hover:text-indigo-600 shadow-sm border border-slate-100 transition-transform hover:scale-105 active:scale-95">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                @auth
                    <a href="{{ route('dashboard') }}" class="hidden md:flex relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl blur opacity-60 group-hover:opacity-100 transition duration-200"></div>
                        <span class="relative px-6 py-2.5 bg-slate-900 rounded-xl text-white text-sm font-bold flex items-center gap-2 group-hover:bg-slate-800 transition-colors">
                            <i class="fas fa-tachometer-alt"></i> DASHBOARD
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:flex relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-fuchsia-500 to-indigo-500 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-200 animate-pulse"></div>
                        <button class="relative px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-fuchsia-600 rounded-xl text-white text-sm font-bold shadow-xl flex items-center gap-2 hover:brightness-110 transition-all">
                            <i class="fas fa-sign-in-alt"></i> MASUK
                        </button>
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden relative z-50 w-11 h-11 flex items-center justify-center rounded-xl bg-white/50 backdrop-blur border border-white/50 shadow-sm text-indigo-900">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars text-xl'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Maximum Colorful Mobile Menu -->
    <div x-show="mobileMenu" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-5"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-5"
         class="lg:hidden absolute top-full left-0 w-full p-4 z-40"
         style="display: none;">
        
        <div class="bg-white/80 backdrop-blur-2xl rounded-3xl p-6 shadow-2xl shadow-indigo-500/20 border border-white/50 space-y-4">
             @foreach([
                ['label' => 'Beranda', 'route' => 'public.home', 'icon' => 'fa-home', 'color' => 'from-blue-400 to-cyan-400'],
                ['label' => 'Berita & Artikel', 'route' => 'public.articles', 'icon' => 'fa-newspaper', 'color' => 'from-fuchsia-400 to-pink-400'],
                ['label' => 'Galeri Multimedia', 'route' => 'public.gallery', 'icon' => 'fa-images', 'color' => 'from-amber-400 to-orange-400'],
            ] as $item)
                <a href="{{ route($item['route']) }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs($item['route'].'*') ? 'bg-slate-50 md:bg-white border-2 border-indigo-100' : 'hover:bg-indigo-50/50' }}">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $item['color'] }} flex items-center justify-center text-white text-lg shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">{{ $item['label'] }}</h4>
                        <p class="text-xs text-slate-500 font-medium">Akses halaman {{ strtolower($item['label']) }}</p>
                    </div>
                </a>
            @endforeach
            
            <div class="pt-4 border-t border-indigo-100">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full py-4 rounded-xl bg-slate-900 text-white font-bold text-center shadow-xl shadow-slate-900/20">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white font-bold text-center shadow-xl shadow-purple-500/30">
                        Login Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
