<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade System - {{ $siteName ?? 'Portal' }}</title>
    
    {{-- Favicon --}}
    @if($faviconUrl ?? false)
        <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            950: '#02040a', // Ultra dark blue/black
                            900: '#090e1a',
                            800: '#131b2e',
                        },
                        primary: {
                            500: '#3b82f6', // Bright Blue
                            600: '#2563eb',
                        },
                        accent: {
                            glow: '#60a5fa',
                        }
                    },
                    backgroundImage: {
                        'grid-white': "linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px)",
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bar-loading': 'bar-loading 2s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        'bar-loading': {
                            '0%': { width: '0%', left: '0%' },
                            '50%': { width: '100%', left: '0%' },
                            '100%': { width: '0%', left: '100%' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark-950 text-white min-h-screen flex items-center justify-center overflow-hidden relative">

    {{-- 1. BACKGROUND LAYERS --}}
    <div class="fixed inset-0 pointer-events-none">
        
        {{-- Very Subtle Grid Background --}}
        <div class="absolute inset-0 bg-grid-white bg-[length:50px_50px] [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_70%)]"></div>

        {{-- Ambient Glows --}}
        <div class="absolute top-[-20%] left-1/2 -translate-x-1/2 w-[800px] h-[600px] bg-primary-500/10 rounded-full blur-[100px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px]"></div>
    </div>

    {{-- 2. MAIN CARD CONTENT --}}
    <div class="relative z-10 w-full max-w-xl px-4 text-center">
        
        {{-- LOGO (Top Centered - Authority) --}}
        <div class="mb-10 flex justify-center animate-float">
            @if($logoUrl ?? false)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-12 w-auto object-contain drop-shadow-[0_0_15px_rgba(59,130,246,0.3)]">
            @else
                <div class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                   <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-500/30">
                       {{ substr($siteName ?? 'P', 0, 1) }}
                   </div>
                   <span class="text-lg font-bold tracking-tight text-white/90">{{ $siteName ?? 'PORTAL' }}</span>
                </div>
            @endif
        </div>

        {{-- MAIN ICON (Modern Circle) --}}
        <div class="mb-10 inline-block relative group">
            <div class="absolute inset-0 bg-primary-500/20 rounded-full blur-xl group-hover:bg-primary-500/30 transition-all duration-500"></div>
            <div class="relative w-24 h-24 rounded-full bg-dark-900 border border-white/10 flex items-center justify-center shadow-2xl ring-4 ring-white/5 group-hover:scale-105 transition-transform duration-500">
                <i data-lucide="wrench" class="w-10 h-10 text-primary-500 relative z-10"></i>
                
                {{-- Decorative Orbit --}}
                <div class="absolute inset-0 rounded-full border border-primary-500/30 border-dashed animate-[spin_10s_linear_infinite]"></div>
            </div>
            
            {{-- Status Pill --}}
            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 bg-dark-800 border border-primary-500/30 rounded-full flex items-center gap-2 shadow-lg">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                <span class="text-[10px] uppercase tracking-wider font-bold text-primary-500">Maintenance</span>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-6 mb-12">
            <h1 class="font-display text-5xl md:text-6xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-white/60 pb-2">
                Segera Kembali
            </h1>
            
            <p class="text-slate-400 text-lg leading-relaxed max-w-md mx-auto font-light">
                Kami sedang melakukan upgrade keamanan sistem untuk pengalaman yang lebih baik. Mohon kembali dalam beberapa saat.
            </p>
        </div>

        {{-- PROGRESS BAR (Minimalist) --}}
        <div class="max-w-xs mx-auto mb-10">
            <div class="flex justify-between text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">
                <span>System Upgrade</span>
                <span class="text-primary-500">Processing...</span>
            </div>
            <div class="h-1 w-full bg-dark-800 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 relative rounded-full">
                    <div class="absolute top-0 bottom-0 bg-white/50 w-full animate-bar-loading rounded-full"></div>
                </div>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button onclick="location.reload()" class="w-full sm:w-auto px-8 py-3 bg-white text-dark-950 font-bold rounded-xl hover:bg-slate-200 transition-colors flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(255,255,255,0.1)] group">
                <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i>
                <span>Refresh Halaman</span>
            </button>
            
            @if($contactEmail ?? false)
            <a href="mailto:{{ $contactEmail }}" class="w-full sm:w-auto px-8 py-3 bg-white/5 border border-white/10 text-slate-300 font-medium rounded-xl hover:bg-white/10 hover:text-white transition-colors flex items-center justify-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <span>Hubungi Kami</span>
            </a>
            @endif
        </div>
        
    </div>

    {{-- FOOTER INFO --}}
    <div class="fixed bottom-6 w-full text-center">
        <p class="text-[10px] text-slate-600 uppercase tracking-[0.2em] opacity-50 hover:opacity-100 transition-opacity">
            Secure Connection • {{ date('Y') }}
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
