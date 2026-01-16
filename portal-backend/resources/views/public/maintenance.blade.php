<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance - {{ $siteName ?? 'BTIKP Portal' }}</title>
    
    {{-- Favicon --}}
    @if($faviconUrl ?? false)
        <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            850: '#151e2e',
                            950: '#020617',
                        },
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #34d399 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen flex items-center justify-center relative overflow-hidden font-sans selection:bg-primary-500/30 selection:text-primary-400">

    {{-- Background Elements --}}
    <div class="absolute inset-0 bg-grid-pattern opacity-20 pointer-events-none"></div>
    
    {{-- Animated Blobs --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-blue-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-purple-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Main Container --}}
    <main class="relative z-10 w-full max-w-5xl px-4 py-8 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-center gap-12 lg:gap-20">
        
        {{-- Left Column: Illustration --}}
        <div class="w-full md:w-1/2 flex justify-center md:justify-end order-1 md:order-2 animate-float">
            <div class="relative w-64 h-64 sm:w-80 sm:h-80 lg:w-[450px] lg:h-[450px]">
                {{-- Abstract Rings --}}
                <div class="absolute inset-0 border border-white/5 rounded-full animate-spin-slow"></div>
                <div class="absolute inset-8 border border-white/10 rounded-full animate-spin-slow" style="animation-direction: reverse; animation-duration: 15s;"></div>
                <div class="absolute inset-16 border border-dashed border-white/10 rounded-full animate-spin-slow" style="animation-duration: 20s;"></div>

                {{-- Center Icon/Illustration --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-tr from-slate-900 to-slate-800 rounded-3xl border border-white/10 shadow-2xl flex items-center justify-center rotate-3 hover:rotate-6 transition-transform duration-500 group">
                        <div class="absolute inset-0 bg-primary-500/20 blur-xl rounded-full group-hover:bg-primary-500/30 transition-all duration-500"></div>
                        <i data-lucide="wrench" class="w-16 h-16 sm:w-20 sm:h-20 text-primary-400 relative z-10"></i>
                        
                        {{-- Floating Tools --}}
                        <div class="absolute -top-6 -right-6 bg-slate-800 p-3 rounded-2xl border border-white/10 shadow-lg animate-float-delayed">
                            <i data-lucide="settings-2" class="w-6 h-6 text-blue-400"></i>
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-slate-800 p-3 rounded-2xl border border-white/10 shadow-lg animate-float" style="animation-delay: 1.5s;">
                            <i data-lucide="shield-check" class="w-6 h-6 text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Content --}}
        <div class="w-full md:w-1/2 text-center md:text-left order-2 md:order-1">
            <div class="glass-panel p-8 sm:p-10 rounded-3xl relative overflow-hidden">
                {{-- Top Line Accents --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-primary-500/50 to-transparent"></div>
                
                {{-- Logo Area --}}
                <div class="mb-8 flex justify-center md:justify-start">
                    @if($logoUrl ?? false)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-10 sm:h-12 w-auto object-contain">
                    @else
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/30">
                                <span class="font-display font-bold text-lg text-white">P</span>
                            </div>
                            <span class="font-display font-bold text-xl text-white tracking-tight">{{ $siteName ?? 'PORTAL' }}</span>
                        </div>
                    @endif
                </div>

                {{-- Status Badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-400 text-xs font-semibold tracking-wide uppercase mb-6 mx-auto md:mx-0">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    System Maintenance
                </div>

                {{-- Title --}}
                <h1 class="font-display font-bold text-4xl sm:text-5xl tracking-tight leading-tight mb-4">
                    Kami sedang melakukan <br>
                    <span class="text-gradient">Upgrade Sistem</span>
                </h1>

                {{-- Description --}}
                <p class="text-slate-400 text-lg leading-relaxed mb-8 max-w-md mx-auto md:mx-0">
                    Mohon maaf atas ketidaknyamanan ini. Kami sedang meningkatkan performa dan keamanan portal untuk pengalaman yang lebih baik.
                </p>

                {{-- Progress Bar --}}
                <div class="mb-8">
                    <div class="flex justify-between text-sm font-medium text-slate-400 mb-2">
                        <span>Pembaruan Berjalan</span>
                        <span class="text-primary-400">85%</span>
                    </div>
                    <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-600 to-primary-400 w-[85%] rounded-full relative">
                            <div class="absolute inset-0 bg-white/20 w-full h-full animate-[shimmer_2s_infinite] skew-x-12"></div>
                        </div>
                    </div>
                </div>

                {{-- Contact Info & Action --}}
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    {{-- Refresh Button --}}
                    <button onclick="location.reload()" class="group relative px-6 py-3 w-full sm:w-auto bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl font-medium transition-all duration-300 flex items-center justify-center gap-2 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-500/0 via-primary-500/10 to-primary-500/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                        <i data-lucide="refresh-cw" class="w-4 h-4 text-primary-400 group-hover:rotate-180 transition-transform duration-500"></i>
                        <span>Cek Status</span>
                    </button>

                    {{-- Contact Link --}}
                    @if($contactEmail)
                    <a href="mailto:{{ $contactEmail }}" class="px-6 py-3 w-full sm:w-auto text-slate-400 hover:text-white transition-colors text-center sm:text-left text-sm font-medium">
                        Hubungi Support &rarr;
                    </a>
                    @endif
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="mt-8 text-center md:text-left text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $siteName ?? 'BTIKP Portal' }}. All rights reserved.</p>
            </div>
        </div>

    </main>

    {{-- Initialize Lucide Icons --}}
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
