@php
    $siteName = \App\Models\SiteSetting::get('site_name', 'BTIKP Portal');
    $logoUrl = \App\Models\SiteSetting::get('logo_url');
    $faviconUrl = \App\Models\SiteSetting::get('favicon_url');
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - Access Point</title>
    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Space Grotesk"', 'sans-serif'],
                    },
                    colors: {
                        brand: { 50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4', 300: '#5eead4', 400: '#2dd4bf', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e', 800: '#115e59', 900: '#134e4a', 950: '#042f2e' },
                        accent: { 500: '#6366f1', 600: '#4f46e5' }
                    },
                    animation: { 'float': 'float 6s ease-in-out infinite', 'pulse-slow': 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite' },
                    keyframes: { float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-20px)' } } }
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @include('auth.partials.login-styles')
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 overflow-hidden selection:bg-brand-500/30">

    {{-- Ambient Background --}}
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[#020617]"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-brand-900/20 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-accent-900/20 rounded-full blur-[120px] animate-pulse-slow" style="animation-delay: 2s;"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px; mask-image: linear-gradient(to bottom, transparent, black, transparent);"></div>
    </div>

    {{-- Main Container --}}
    <div class="relative z-10 w-full max-w-[1100px] h-[80vh] min-h-[600px] max-h-[800px] glass-card rounded-[32px] overflow-hidden flex flex-row shadow-2xl">
        
        {{-- Left Panel --}}
        @include('auth.partials.login-left-panel')

        {{-- Right Panel (Dynamic Content) --}}
        <div x-data="authPanel()" class="flex-1 md:flex-none w-full md:w-[50%] relative bg-[#0b1120]/80 flex flex-col h-full">
            
            {{-- Navigation Tabs --}}
            <nav class="flex-none flex w-full border-b border-white/5">
                <button @click="switchMode('login')" 
                        class="flex-1 h-20 flex items-center justify-center text-sm font-semibold tracking-wide transition-all relative group"
                        :class="mode === 'login' ? 'text-white bg-white/[0.02]' : 'text-slate-500 hover:text-slate-300'"
                        x-show="mode !== 'reset'">
                    Login Area
                    <div class="absolute bottom-0 w-full h-[2px] bg-brand-500 scale-x-0 transition-transform duration-300 origin-center"
                         :class="mode === 'login' ? 'scale-x-100' : 'scale-x-0'"></div>
                </button>
                <button @click="switchMode('register')" 
                        class="flex-1 h-20 flex items-center justify-center text-sm font-semibold tracking-wide transition-all relative group"
                        :class="mode === 'register' ? 'text-white bg-white/[0.02]' : 'text-slate-500 hover:text-slate-300'"
                        x-show="mode !== 'reset'">
                    Registrasi
                    <div class="absolute bottom-0 w-full h-[2px] bg-brand-500 scale-x-0 transition-transform duration-300 origin-center"
                         :class="mode === 'register' ? 'scale-x-100' : 'scale-x-0'"></div>
                </button>
                <div class="flex-1 h-20 flex items-center justify-center text-sm font-semibold tracking-wide text-white bg-white/[0.02] relative"
                     x-show="mode === 'reset'" x-cloak>
                    <i data-lucide="key-round" class="w-4 h-4 mr-2"></i>
                    Reset Password
                    <div class="absolute bottom-0 w-full h-[2px] bg-amber-500"></div>
                </div>
                <button @click="switchMode('login')" 
                        class="w-20 h-20 flex items-center justify-center text-slate-500 hover:text-white hover:bg-white/5 transition-all"
                        x-show="mode === 'reset'" x-cloak
                        title="Kembali ke Login">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </nav>

            {{-- Content Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-8 md:p-14 relative">
                
                {{-- Alert Messages --}}
                <div x-show="errorMessage" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 shrink-0 mt-0.5"></i>
                    <p class="text-sm text-red-300" x-text="errorMessage"></p>
                </div>

                <div x-show="successMessage" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                    <p class="text-sm text-emerald-300" x-text="successMessage"></p>
                </div>

                {{-- Form Partials --}}
                @include('auth.partials.login-form')
                @include('auth.partials.register-form')
                @include('auth.partials.reset-password-form')
            </div>
            
            <div class="h-1 w-full bg-gradient-to-r from-transparent via-brand-500/20 to-transparent"></div>
        </div>
    </div>

    @include('auth.partials.login-scripts')
</body>
</html>