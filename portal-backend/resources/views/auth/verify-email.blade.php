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
    <title>{{ $siteName }} - Verifikasi Email</title>
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
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
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
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
        
        .modern-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .modern-input:focus {
            background: rgba(255, 255, 255, 0.08); /* Lighter bg on focus */
            border-color: #14b8a6; /* Brand 500 */
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
            outline: none;
        }
        
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
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
    <div class="relative z-10 w-full max-w-md glass-card rounded-3xl overflow-hidden shadow-2xl p-8">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500/20 to-accent-500/20 text-brand-400 mb-6 border border-white/5 shadow-inner">
                <i data-lucide="mail-check" class="w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Verifikasi Email</h2>
            <p class="text-slate-400 text-sm">Kode OTP telah dikirim ke <span class="text-white font-medium">{{ $email }}</span></p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                <p class="text-sm text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 shrink-0 mt-0.5"></i>
                <p class="text-sm text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('verification.verify') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="space-y-2">
                <label for="otp" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Kode Verifikasi (OTP)</label>
                <input type="text" 
                       id="otp"
                       name="otp"
                       required
                       maxlength="6"
                       class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-600 text-center text-2xl font-mono tracking-[0.5em] font-bold @error('otp') border-red-500 @enderror" 
                       placeholder="000000">
                @error('otp')
                    <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 uppercase">
                Verifikasi Akun
            </button>
        </form>

        <div class="mt-6 text-center">
            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <p class="text-sm text-slate-500">
                    Tidak menerima kode? 
                    <button type="submit" class="text-brand-400 font-bold hover:text-brand-300 hover:underline transition-all">Kirim Ulang</button>
                </p>
            </form>
            
            <div class="mt-8 pt-6 border-t border-white/5">
                <a href="{{ route('login') }}" class="text-xs font-medium text-slate-500 hover:text-white transition-colors flex items-center justify-center gap-2 group">
                    <i data-lucide="arrow-left" class="w-3 h-3 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
