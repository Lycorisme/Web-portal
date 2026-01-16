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

    {{-- Fonts (Google Fonts) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Icons (Lucide) --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            900: '#0B0F19', // Deep dark blue/black
                            800: '#111827',
                            700: '#1F2937',
                        },
                        brand: {
                            500: '#3B82F6', // Blue primary
                            400: '#60A5FA',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 12s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        'pulse-glow': {
                            '0%, 100%': { opacity: '0.4', transform: 'scale(1)' },
                            '50%': { opacity: '0.8', transform: 'scale(1.1)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark-900 text-white min-h-screen flex items-center justify-center overflow-hidden relative selection:bg-brand-500/30 selection:text-brand-400">

    {{-- Background Animated Gradients (Pure Tailwind Classes) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        {{-- Top Center Glow --}}
        <div class="absolute -top-1/2 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-brand-500/20 rounded-full blur-[120px] animate-pulse-glow"></div>
        {{-- Bottom Left Glow --}}
        <div class="absolute -bottom-1/2 -left-1/4 w-[600px] h-[600px] bg-purple-500/10 rounded-full blur-[100px]"></div>
        {{-- Bottom Right Glow --}}
        <div class="absolute -bottom-1/2 -right-1/4 w-[600px] h-[600px] bg-teal-500/10 rounded-full blur-[100px]"></div>
    </div>

    {{-- Main Content Container (Centered) --}}
    <div class="relative z-10 w-full max-w-lg px-6 flex flex-col items-center text-center">
        
        {{-- Floating Icon/Illustration --}}
        <div class="mb-10 relative group animate-float">
            {{-- Glowing Ring Behind --}}
            <div class="absolute inset-0 bg-brand-500/20 rounded-full blur-xl group-hover:bg-brand-500/30 transition-all duration-500"></div>
            
            {{-- Icon Circle --}}
            <div class="relative w-24 h-24 bg-dark-800/80 backdrop-blur-xl border border-white/10 rounded-3xl flex items-center justify-center shadow-2xl shadow-brand-500/20 ring-1 ring-white/5">
                <i data-lucide="wrench" class="w-10 h-10 text-brand-400"></i>
                
                {{-- Decorative Orbit Dot --}}
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-brand-400 rounded-full animate-ping"></div>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-brand-500 rounded-full"></div>
            </div>

            {{-- Small Decorative Icons --}}
            <div class="absolute -left-8 top-1/2 p-2 bg-dark-800/80 backdrop-blur border border-white/5 rounded-xl animate-bounce" style="animation-delay: 1s">
                <i data-lucide="shield" class="w-4 h-4 text-purple-400"></i>
            </div>
            <div class="absolute -right-8 top-1/3 p-2 bg-dark-800/80 backdrop-blur border border-white/5 rounded-xl animate-bounce" style="animation-delay: 2s">
                <i data-lucide="server" class="w-4 h-4 text-teal-400"></i>
            </div>
        </div>

        {{-- Typography --}}
        <div class="space-y-6">
            {{-- Status Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                <span class="text-xs font-semibold tracking-wider text-slate-300 uppercase">System Maintenance</span>
            </div>

            {{-- Main Title --}}
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400">
                Segera Kembali
            </h1>

            {{-- Description --}}
            <p class="text-lg text-slate-400 leading-relaxed max-w-md mx-auto">
                Kami sedang melakukan peningkatan sistem untuk performa yang lebih baik. Mohon maaf atas ketidaknyamanan ini.
            </p>
        </div>

        {{-- Progress Indicator --}}
        <div class="w-full max-w-xs mt-10">
            <div class="flex justify-between text-xs text-slate-500 mb-2 font-medium">
                <span>Updating System</span>
                <span>In Progress...</span>
            </div>
            <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-brand-500 w-1/3 rounded-full animate-[loading_2s_ease-in-out_infinite] relative">
                    <div class="absolute inset-0 bg-white/30 blur-sm"></div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-12 flex flex-col sm:flex-row items-center gap-4 w-full justify-center">
            <button onclick="location.reload()" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-dark-900 font-semibold hover:bg-slate-200 transition-colors flex items-center justify-center gap-2 group">
                <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i>
                <span>Coba Refresh</span>
            </button>
            
            @if($contactEmail ?? false)
            <a href="mailto:{{ $contactEmail }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-slate-300 font-medium transition-colors flex items-center justify-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <span>Hubungi Kami</span>
            </a>
            @endif
        </div>

        {{-- Footer --}}
        <div class="mt-16 text-center">
             @if($logoUrl ?? false)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-6 w-auto mx-auto opacity-50 grayscale hover:grayscale-0 transition-all duration-300">
            @else
                <span class="text-sm font-semibold text-slate-600 tracking-widest uppercase">{{ $siteName ?? 'PORTAL' }}</span>
            @endif
        </div>
    </div>

    {{-- Script for initializing icons --}}
    <script>
        lucide.createIcons();
        
        // Add custom loading animation keyframe programmatically since we can't use style tag
        tailwind.config.theme.extend.keyframes.loading = {
            '0%': { width: '0%', marginLeft: '0%' },
            '50%': { width: '100%', marginLeft: '0%' },
            '100%': { width: '0%', marginLeft: '100%' }
        };
    </script>
</body>
</html>
