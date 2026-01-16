<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance - {{ $siteName ?? 'Portal' }}</title>
    
    {{-- Favicon --}}
    @if($faviconUrl ?? false)
        <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @endif

    {{-- Fonts (Matching Public Layout) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    // Styles matching your public.blade.php layout
                    colors: {
                        slate: {
                            850: '#151e2e',
                            950: '#020617', // Your main background color
                        },
                        // Using Emerald as primary accent like in your site
                        primary: {
                            400: '#34d399',
                            500: '#10b981', 
                            600: '#059669',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    backgroundImage: {
                        'grid-white': "linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px)",
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bar-loading': 'bar-loading 2s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
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
<body class="bg-slate-950 text-slate-200 min-h-screen flex items-center justify-center overflow-hidden relative antialiased selection:bg-emerald-500/30 selection:text-emerald-400">

    {{-- 1. BACKGROUND LAYERS (Sama persis dengan Public Layout) --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        
        {{-- Subtle Grid (Yang Anda suka di desain maintenance) --}}
        <div class="absolute inset-0 bg-grid-white bg-[length:50px_50px] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)] opacity-50"></div>

        {{-- Floating Blobs (Dari Layout Utama) --}}
        <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px] animate-float-slow"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-emerald-500/10 rounded-full blur-[120px] animate-float-slow" style="animation-delay: 2s"></div>
        <div class="absolute top-[40%] left-[30%] w-[40%] h-[40%] bg-blue-500/10 rounded-full blur-[150px] animate-float" style="animation-delay: -3s"></div>
    </div>

    {{-- 2. MAIN CARD CONTENT --}}
    <div class="relative z-10 w-full max-w-xl px-4 text-center">
        
        {{-- LOGO --}}
        <div class="mb-10 flex justify-center animate-float">
            @if($logoUrl ?? false)
                <!-- Logo Image with Glow -->
                <div class="relative">
                    <div class="absolute inset-0 bg-emerald-500/20 blur-xl rounded-full"></div>
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="relative h-12 w-auto object-contain drop-shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                </div>
            @else
                <!-- Fallback Logo -->
                <div class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl shadow-emerald-500/5">
                   <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white font-bold font-display text-lg shadow-lg shadow-emerald-500/30">
                       {{ substr($siteName ?? 'P', 0, 1) }}
                   </div>
                   <span class="text-lg font-bold font-display tracking-tight text-white/90">{{ $siteName ?? 'PORTAL' }}</span>
                </div>
            @endif
        </div>

        {{-- MAIN ICON (Theme Color Updated) --}}
        <div class="mb-10 inline-block relative group">
            <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl group-hover:bg-emerald-500/30 transition-all duration-500"></div>
            <div class="relative w-24 h-24 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center shadow-2xl ring-4 ring-white/5 group-hover:scale-105 transition-transform duration-500">
                <i data-lucide="wrench" class="w-10 h-10 text-emerald-500 relative z-10"></i>
                
                {{-- Decorative Orbit --}}
                <div class="absolute inset-0 rounded-full border border-emerald-500/30 border-dashed animate-[spin_10s_linear_infinite]"></div>
            </div>
            
            {{-- Status Pill --}}
            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 bg-slate-900 border border-emerald-500/30 rounded-full flex items-center gap-2 shadow-lg">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-500 font-display">Maintenance</span>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-6 mb-12">
            {{-- Gradient Text Matching Site Theme --}}
            <h1 class="font-display text-5xl md:text-6xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-white/60 pb-3">
                Segera Kembali
            </h1>
            
            <p class="text-slate-400 text-lg leading-relaxed max-w-md mx-auto font-light">
                Kami sedang melakukan upgrade keamanan sistem untuk pengalaman yang lebih baik. Mohon kembali dalam beberapa saat.
            </p>
        </div>

        {{-- PROGRESS BAR (Updated Color) --}}
        <div class="max-w-xs mx-auto mb-10">
            <div class="flex justify-between text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide font-display">
                <span>System Upgrade</span>
                <span class="text-emerald-500">Processing...</span>
            </div>
            <div class="h-1 w-full bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 relative rounded-full">
                    <div class="absolute top-0 bottom-0 bg-white/50 w-full animate-bar-loading rounded-full"></div>
                </div>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button onclick="location.reload()" class="w-full sm:w-auto px-8 py-3 bg-white text-slate-950 font-bold font-display rounded-xl hover:bg-emerald-50 transition-colors flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(16,185,129,0.1)] group">
                <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500 text-emerald-600"></i>
                <span>Refresh Halaman</span>
            </button>
            
            @if($contactEmail ?? false)
            <a href="mailto:{{ $contactEmail }}" class="w-full sm:w-auto px-8 py-3 bg-white/5 border border-white/10 text-slate-300 font-medium font-display rounded-xl hover:bg-white/10 hover:text-white transition-colors flex items-center justify-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <span>Hubungi Kami</span>
            </a>
            @endif
        </div>
        
    </div>

    {{-- FOOTER INFO --}}
    <div class="fixed bottom-6 w-full text-center">
        <p class="text-[10px] text-slate-600 uppercase tracking-[0.2em] font-medium font-display opacity-60 hover:opacity-100 transition-opacity">
            {{ $siteName ?? 'Portal' }} &copy; {{ date('Y') }}
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
