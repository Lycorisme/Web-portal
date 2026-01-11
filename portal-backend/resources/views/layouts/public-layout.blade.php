<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('meta_title', 'Beranda') | {{ $siteSettings['site_name'] ?? 'BTIKP PORTAL' }} - Digital Excellence</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['site_tagline'] ?? 'Portal Berita & Informasi')">
    
    <!-- Scripts & Fonts -->
    <script src="https://cdn.tailwindcss.com?plugins=typography,aspect-ratio,line-clamp"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100vw; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.5); }
        img, video, iframe, embed, object { max-width: 100%; height: auto; }
        pre, code { overflow-x: auto; max-width: 100%; }
        table { display: block; overflow-x: auto; max-width: 100%; }
        p, h1, h2, h3, h4, h5, h6, li, span, a { word-wrap: break-word; overflow-wrap: break-word; }
    </style>
</head>
<body class="bg-[#020617] text-slate-200 overflow-x-hidden" x-data="{ mobileMenu: false }">

    <!-- Background Glow Effects -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-emerald-600/20 blur-[150px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-violet-600/20 blur-[150px] rounded-full"></div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('public.home') }}">
                    @if(isset($siteSettings['logo_url']) && $siteSettings['logo_url'])
                        <img class="h-12 w-auto" src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl flex items-center justify-center shadow-xl shadow-emerald-500/20 border border-emerald-400/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    @endif
                </a>
                <div>
                    <h1 class="text-xl font-extrabold text-white tracking-tighter leading-none">
                        {{ strtoupper($siteSettings['site_name'] ?? 'BTIKP') }} <span class="text-emerald-500">PORTAL</span>
                    </h1>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em]">Digital Excellence</p>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-10 text-sm font-bold tracking-wide text-slate-400">
                <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'text-emerald-500' : 'hover:text-emerald-400' }} transition-all">BERANDA</a>
                <a href="{{ route('public.articles') }}" class="{{ request()->routeIs('public.articles*') ? 'text-emerald-500' : 'hover:text-emerald-400' }} transition-all">BERITA</a>
                <a href="{{ route('public.gallery') }}" class="{{ request()->routeIs('public.gallery') ? 'text-emerald-500' : 'hover:text-emerald-400' }} transition-all">GALERI</a>
                <!-- Placeholder for Pengumuman if needed in future -->
            </div>

            <div class="flex items-center gap-6">
                <button class="text-slate-400 hover:text-white transition-all hidden sm:block">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all hover:bg-slate-700 uppercase">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white text-xs font-extrabold rounded-xl transition-all shadow-lg shadow-emerald-900/40 uppercase">Masuk Portal</a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" 
             style="display: none;"
             x-transition
             class="lg:hidden bg-slate-950 border-b border-slate-800 absolute w-full left-0 top-20 z-40 p-6 space-y-4">
            <a href="{{ route('public.home') }}" class="block text-sm font-bold tracking-wide {{ request()->routeIs('public.home') ? 'text-emerald-500' : 'text-slate-400' }}">BERANDA</a>
            <a href="{{ route('public.articles') }}" class="block text-sm font-bold tracking-wide {{ request()->routeIs('public.articles*') ? 'text-emerald-500' : 'text-slate-400' }}">BERITA</a>
            <a href="{{ route('public.gallery') }}" class="block text-sm font-bold tracking-wide {{ request()->routeIs('public.gallery') ? 'text-emerald-500' : 'text-slate-400' }}">GALERI</a>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-slate-950 border-t border-slate-900 pt-20 pb-10 mt-auto">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
            <div class="col-span-1 md:col-span-2 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center font-black text-white">
                        {{ substr($siteSettings['site_name'] ?? 'B', 0, 1) }}
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter uppercase italic">{{ $siteSettings['site_name'] ?? 'BTIKP Portal' }}</span>
                </div>
                <p class="text-slate-500 text-sm leading-relaxed max-w-sm">
                    {{ $siteSettings['site_tagline'] ?? 'Pusat informasi dan transformasi teknologi komunikasi pendidikan.' }}
                </p>
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all cursor-pointer uppercase text-[10px] font-black">FB</div>
                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all cursor-pointer uppercase text-[10px] font-black">IG</div>
                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all cursor-pointer uppercase text-[10px] font-black">YT</div>
                </div>
            </div>
            <div>
                <h5 class="text-white font-black uppercase text-xs tracking-widest mb-6">Tautan Cepat</h5>
                <ul class="space-y-4 text-slate-500 text-sm font-semibold">
                    <li><a href="{{ route('public.home') }}" class="hover:text-emerald-500 transition-all">Beranda</a></li>
                    <li><a href="{{ route('public.articles') }}" class="hover:text-emerald-500 transition-all">Berita</a></li>
                    <li><a href="{{ route('public.gallery') }}" class="hover:text-emerald-500 transition-all">Galeri</a></li>
                    <li><a href="#" class="hover:text-emerald-500 transition-all">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-black uppercase text-xs tracking-widest mb-6">Alamat Kantor</h5>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Jl. Perdagangan No.106, Banjarmasin Utara, Kota Banjarmasin, Kalimantan Selatan 70123
                </p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-10 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs font-bold text-slate-700 uppercase tracking-widest">© {{ date('Y') }} {{ strtoupper($siteSettings['site_name'] ?? 'BTIKP PORTAL') }}. DEVELOPED FOR DIGITAL EXCELLENCE.</p>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">System Online & Secure</span>
            </div>
        </div>
    </footer>

</body>
</html>
