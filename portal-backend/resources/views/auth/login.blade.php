@php
    $siteName = $siteName ?? 'BTIKP Portal';
    $logoUrl = $logoUrl ?? '';
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ $siteName }}</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        theme: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                            950: '#450a0a',
                        },
                        surface: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        space: ['Space Grotesk', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        .font-space {
            font-family: 'Space Grotesk', sans-serif;
        }
        .bg-theme-gradient {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
        }
        .shadow-theme {
            box-shadow: 0 10px 40px -10px rgba(220, 38, 38, 0.3);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="h-full bg-surface-950 text-white overflow-hidden">
    {{-- Background Elements --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        {{-- Gradient Orbs --}}
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-theme-600/30 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-theme-700/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-theme-500/10 rounded-full blur-3xl"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.02)_1px,transparent_1px)] bg-[size:50px_50px]"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            {{-- Logo & Title --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center mb-6">
                    @if(!empty($logoUrl))
                        <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-theme animate-float">
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-theme-gradient flex items-center justify-center shadow-theme animate-float">
                            <span class="text-white font-space font-bold text-3xl">{{ strtoupper(substr($siteName, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <h1 class="font-space text-3xl font-bold text-white mb-2">{{ $siteName }}</h1>
                <p class="text-surface-400">Masuk ke panel administrasi</p>
            </div>

            {{-- Login Card --}}
            <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-red-300">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    {{-- Email Field --}}
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-surface-300">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-surface-500"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus
                                autocomplete="email"
                                class="w-full pl-12 pr-4 py-3.5 bg-surface-900/50 border border-surface-700/50 rounded-xl text-white placeholder:text-surface-500 focus:outline-none focus:border-theme-500 focus:ring-2 focus:ring-theme-500/20 transition-all duration-200"
                                placeholder="admin@gmail.com"
                            >
                        </div>
                    </div>

                    {{-- Password Field --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-surface-300">Password</label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-surface-500"></i>
                            </div>
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                id="password" 
                                name="password" 
                                required
                                autocomplete="current-password"
                                class="w-full pl-12 pr-12 py-3.5 bg-surface-900/50 border border-surface-700/50 rounded-xl text-white placeholder:text-surface-500 focus:outline-none focus:border-theme-500 focus:ring-2 focus:ring-theme-500/20 transition-all duration-200"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-surface-500 hover:text-surface-300 transition-colors"
                            >
                                <i x-show="!showPassword" data-lucide="eye" class="w-5 h-5"></i>
                                <i x-show="showPassword" data-lucide="eye-off" class="w-5 h-5" x-cloak></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-theme-500 focus:ring-theme-500/20 focus:ring-offset-0 transition-colors"
                            >
                            <span class="text-sm text-surface-400 group-hover:text-surface-300 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit"
                        class="relative w-full py-4 px-6 bg-theme-gradient rounded-xl font-semibold text-white shadow-theme hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 overflow-hidden group"
                    >
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <i data-lucide="log-in" class="w-5 h-5"></i>
                            Masuk
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                    </button>
                </form>

                {{-- Security Notice --}}
                <div class="mt-6 pt-6 border-t border-surface-700/30">
                    <div class="flex items-center gap-3 text-xs text-surface-500">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                        <span>Koneksi aman dengan enkripsi SSL</span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-surface-500 text-sm mt-8">
                &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </p>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
