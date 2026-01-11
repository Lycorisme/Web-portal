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
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        },
                        accent: {
                            500: '#6366f1',
                            600: '#4f46e5',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617; /* Slate 950 */
            color: #ffffff;
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Input Styles */
        .modern-input {
            background: rgba(30, 41, 59, 0.5); /* Darker background */
            border: 1px solid rgba(148, 163, 184, 0.1);
            transition: all 0.3s ease;
            color: white;
        }
        .modern-input:focus {
            background: rgba(30, 41, 59, 0.8);
            border-color: #2dd4bf;
            box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.1);
            outline: none;
        }
        .modern-input::placeholder {
            color: #64748b;
        }
        
        /* Autofill Styling Fix */
        .modern-input:-webkit-autofill,
        .modern-input:-webkit-autofill:hover, 
        .modern-input:-webkit-autofill:focus, 
        .modern-input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Custom Scrollbar for Right Pane */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 overflow-hidden selection:bg-brand-500/30">

    <!-- Ambient Background -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[#020617]"></div>
        <!-- Decorative blobs -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-brand-900/20 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-accent-900/20 rounded-full blur-[120px] animate-pulse-slow" style="animation-delay: 2s;"></div>
        
        <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px; mask-image: linear-gradient(to bottom, transparent, black, transparent);"></div>
    </div>

    <!-- Main Container -->
    <!-- Used Flex row explicitly with fixed container sizing to ensure strict independence -->
    <div class="relative z-10 w-full max-w-[1100px] h-[80vh] min-h-[600px] max-h-[800px] glass-card rounded-[32px] overflow-hidden flex flex-row shadow-2xl">
        
        <!-- =======================
             LEFT COLUMN (Static)
             Strict width: 50%, Flex-none to prevent shrinking/growing
        ======================== -->
        <div class="flex-none w-[50%] relative hidden md:flex flex-col justify-between p-12 lg:p-16 border-r border-white/5 overflow-hidden">
            <!-- Background effects specific to left side -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/[0.03] to-transparent pointer-events-none"></div>
            
            <!-- Animated rings independent of content -->
            <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] border border-white/5 rounded-full opacity-50 pointer-events-none"></div>
            <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-[350px] h-[350px] border border-white/5 rounded-full opacity-70 pointer-events-none"></div>

            <!-- Header: Logo/Brand -->
            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-12 w-auto rounded-xl shadow-lg shadow-brand-500/20">
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/20">
                            <i data-lucide="layers" class="text-white w-7 h-7"></i>
                        </div>
                    @endif
                    <span class="text-xl font-bold tracking-tight text-white">{{ $siteName }}</span>
                </div>
            </div>

            <!-- Middle: Hero Text (Vertically centered by flex logic) -->
            <div class="relative z-10 my-auto">
                <h1 class="font-display text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight mb-8">
                    Digital <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Excellence.</span>
                </h1>
                <p class="text-lg text-slate-400 leading-relaxed font-light max-w-sm">
                    Satu platform terintegrasi untuk mengelola seluruh ekosistem digital Anda dengan keamanan tingkat tinggi.
                </p>
            </div>

            <!-- Footer: Badges -->
            <div class="relative z-10 mt-auto pt-8">
                <div class="flex gap-4">
                    <div class="px-5 py-2.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm flex items-center gap-3 group hover:bg-white/10 transition-colors cursor-default">
                        <div class="w-2 h-2 rounded-full bg-brand-400 shadow-[0_0_10px_theme(colors.brand.400)]"></div>
                        <span class="text-[10px] font-bold tracking-widest uppercase text-slate-300 group-hover:text-white transition-colors">Secure Access</span>
                    </div>
                    <div class="px-5 py-2.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm flex items-center gap-3 group hover:bg-white/10 transition-colors cursor-default">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-accent-400"></i>
                        <span class="text-[10px] font-bold tracking-widest uppercase text-slate-300 group-hover:text-white transition-colors">Real-time Sync</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- =======================
             RIGHT COLUMN (Dynamic)
             Strict width: 50%, Flex-none
        ======================== -->
        <div x-data="authPanel()" class="flex-1 md:flex-none w-full md:w-[50%] relative bg-[#0b1120]/80 flex flex-col h-full">
            
            <!-- Navbar / Tabs -->
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
                <!-- Reset Password Tab (only shown when in reset mode) -->
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

            <!-- Content Area: Independent Scroll -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-8 md:p-14 relative">
                
                <!-- Alert Messages -->
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

                <!-- Login View -->
                <div x-show="mode === 'login'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
                        <p class="text-slate-400">Masuk untuk mengakses dashboard Anda.</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Identity</label>
                            <input type="email" 
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium @error('email') border-red-500 @enderror" 
                                   placeholder="username@btikp.id">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Security Code</label>
                            <input type="password" 
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest @error('password') border-red-500 @enderror" 
                                   placeholder="••••••••">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <label class="flex items-center gap-3 cursor-pointer group" for="remember" x-data="{ checked: {{ old('remember') ? 'true' : 'false' }} }">
                                <div class="w-5 h-5 rounded border flex items-center justify-center transition-all duration-200 relative"
                                     :class="checked ? 'border-brand-500 bg-brand-500' : 'border-slate-600 bg-slate-800 group-hover:border-brand-500'">
                                    <input type="checkbox" 
                                           name="remember" 
                                           id="remember" 
                                           value="1"
                                           class="absolute inset-0 opacity-0 cursor-pointer z-10" 
                                           x-model="checked">
                                    <svg x-show="checked" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-slate-400 group-hover:text-slate-300">Ingat Saya</span>
                            </label>
                            <button type="button" @click="switchMode('reset')" class="text-sm font-medium text-brand-400 hover:text-brand-300 transition-colors">Lupa Password?</button>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4">
                            Masuk Portal
                        </button>
                    </form>
                </div>

                <!-- Register View -->
                <div x-show="mode === 'register'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Akun Baru</h2>
                        <p class="text-slate-400">Daftarkan diri Anda ke dalam sistem.</p>
                    </div>

                    <form action="#" class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Nama Depan</label>
                                <input type="text" class="w-full px-4 py-3.5 rounded-xl modern-input text-white" placeholder="John">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Nama Belakang</label>
                                <input type="text" class="w-full px-4 py-3.5 rounded-xl modern-input text-white" placeholder="Doe">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email Dinas</label>
                            <input type="email" class="w-full px-5 py-4 rounded-xl modern-input text-white" placeholder="nama@instansi.go.id">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">NIP / ID Pegawai</label>
                            <input type="text" class="w-full px-5 py-4 rounded-xl modern-input text-white" placeholder="199XXXXXXXX">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Password</label>
                            <input type="password" class="w-full px-5 py-4 rounded-xl modern-input text-white" placeholder="••••••••">
                        </div>

                        <div class="p-4 rounded-xl bg-white/5 border border-white/5 mt-2">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="w-5 h-5 rounded border border-slate-600 bg-slate-800 flex items-center justify-center transition-colors group-hover:border-brand-500 relative shrink-0 mt-0.5">
                                    <input type="checkbox" class="absolute inset-0 opacity-0 cursor-pointer peer">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <span class="text-xs text-slate-400 leading-relaxed group-hover:text-slate-300">
                                    Saya menyetujui seluruh kebijakan privasi dan aturan keamanan data yang berlaku di {{ $siteName }}.
                                </span>
                            </label>
                        </div>

                        <button class="w-full py-4 rounded-xl bg-slate-800 border border-white/10 hover:bg-white/5 text-white font-bold text-sm tracking-widest transition-all duration-300 uppercase mt-4">
                            Ajukan Pendaftaran
                        </button>
                    </form>
                </div>

                <!-- Reset Password View -->
                <div x-show="mode === 'reset'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>

                    <!-- Step 1: Email Verification -->
                    <div x-show="resetStep === 1">
                        <div class="mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-6">
                                <i data-lucide="mail" class="w-7 h-7 text-amber-400"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Lupa Password?</h2>
                            <p class="text-slate-400">Masukkan email terdaftar Anda. Kami akan mengirimkan kode OTP untuk verifikasi.</p>
                        </div>

                        <form @submit.prevent="sendOtp()" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email Terdaftar</label>
                                <input type="email" 
                                       x-model="resetEmail"
                                       required
                                       class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                                       placeholder="masukkan email anda">
                            </div>

                            <button type="submit" 
                                    :disabled="isLoading"
                                    class="w-full py-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-sm tracking-widest shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <template x-if="isLoading">
                                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <span x-text="isLoading ? 'Mengirim...' : 'Kirim Kode OTP'"></span>
                            </button>

                            <p class="text-center text-sm text-slate-500 mt-4">
                                Ingat password? 
                                <button type="button" @click="switchMode('login')" class="text-brand-400 hover:text-brand-300 font-medium">Kembali ke Login</button>
                            </p>
                        </form>
                    </div>

                    <!-- Step 2: OTP Verification -->
                    <div x-show="resetStep === 2">
                        <div class="mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-6">
                                <i data-lucide="smartphone" class="w-7 h-7 text-emerald-400"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Verifikasi OTP</h2>
                            <p class="text-slate-400">
                                Masukkan kode 6 digit yang telah dikirim ke 
                                <span class="text-brand-400 font-medium" x-text="maskedEmail"></span>
                            </p>
                        </div>

                        <form @submit.prevent="verifyOtp()" class="space-y-6">
                            <!-- OTP Input Boxes -->
                            <div class="flex justify-center gap-3">
                                <template x-for="(digit, index) in otpDigits" :key="index">
                                    <input type="text" 
                                           maxlength="1"
                                           x-model="otpDigits[index]"
                                           @input="handleOtpInput($event, index)"
                                           @keydown.backspace="handleOtpBackspace($event, index)"
                                           @paste="handleOtpPaste($event)"
                                           :id="'otp-' + index"
                                           class="w-12 h-14 text-center text-2xl font-bold rounded-xl modern-input text-white focus:border-emerald-500 focus:ring-emerald-500/20"
                                           pattern="[0-9]"
                                           inputmode="numeric">
                                </template>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="text-center">
                                <p class="text-sm text-slate-500" x-show="countdown > 0">
                                    Kode berlaku dalam <span class="text-amber-400 font-mono font-bold" x-text="formatCountdown()"></span>
                                </p>
                                <p class="text-sm text-red-400" x-show="countdown <= 0">
                                    Kode OTP sudah kadaluarsa
                                </p>
                            </div>

                            <button type="submit" 
                                    :disabled="isLoading || otpDigits.join('').length !== 6"
                                    class="w-full py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold text-sm tracking-widest shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <template x-if="isLoading">
                                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <span x-text="isLoading ? 'Memverifikasi...' : 'Verifikasi Kode'"></span>
                            </button>

                            <div class="text-center space-y-2">
                                <p class="text-sm text-slate-500">
                                    Tidak menerima kode? 
                                    <button type="button" 
                                            @click="resendOtp()"
                                            :disabled="resendCooldown > 0 || isLoading"
                                            class="text-brand-400 hover:text-brand-300 font-medium disabled:text-slate-600 disabled:cursor-not-allowed">
                                        <span x-show="resendCooldown <= 0">Kirim Ulang</span>
                                        <span x-show="resendCooldown > 0" x-text="'Tunggu ' + resendCooldown + 's'"></span>
                                    </button>
                                </p>
                                <button type="button" @click="resetStep = 1" class="text-sm text-slate-500 hover:text-slate-300">
                                    ← Ubah email
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 3: New Password -->
                    <div x-show="resetStep === 3">
                        <div class="mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-brand-500/10 flex items-center justify-center mb-6">
                                <i data-lucide="lock" class="w-7 h-7 text-brand-400"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Password Baru</h2>
                            <p class="text-slate-400">Buat password baru yang kuat untuk akun Anda.</p>
                        </div>

                        <form @submit.prevent="resetPassword()" class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Password Baru</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" 
                                           x-model="newPassword"
                                           required
                                           minlength="8"
                                           class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium pr-12" 
                                           placeholder="Minimal 8 karakter">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                                        <i data-lucide="eye" class="w-5 h-5" x-show="!showPassword"></i>
                                        <i data-lucide="eye-off" class="w-5 h-5" x-show="showPassword" x-cloak></i>
                                    </button>
                                </div>
                                <!-- Password Strength Indicator -->
                                <div class="flex gap-1 mt-2">
                                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-slate-700'"></div>
                                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 2 ? 'bg-amber-500' : 'bg-slate-700'"></div>
                                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 3 ? 'bg-emerald-500' : 'bg-slate-700'"></div>
                                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 4 ? 'bg-brand-500' : 'bg-slate-700'"></div>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    <span x-show="passwordStrength === 0">Masukkan password</span>
                                    <span x-show="passwordStrength === 1" class="text-red-400">Lemah</span>
                                    <span x-show="passwordStrength === 2" class="text-amber-400">Cukup</span>
                                    <span x-show="passwordStrength === 3" class="text-emerald-400">Kuat</span>
                                    <span x-show="passwordStrength === 4" class="text-brand-400">Sangat Kuat</span>
                                </p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Konfirmasi Password</label>
                                <input type="password" 
                                       x-model="confirmPassword"
                                       required
                                       class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                                       :class="confirmPassword && confirmPassword !== newPassword ? 'border-red-500' : ''"
                                       placeholder="Ulangi password baru">
                                <p class="text-xs text-red-400 mt-1" x-show="confirmPassword && confirmPassword !== newPassword">
                                    Password tidak cocok
                                </p>
                            </div>

                            <button type="submit" 
                                    :disabled="isLoading || !newPassword || newPassword !== confirmPassword || passwordStrength < 2"
                                    class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <template x-if="isLoading">
                                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <span x-text="isLoading ? 'Menyimpan...' : 'Simpan Password Baru'"></span>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
            
            <!-- Bottom decorative line -->
            <div class="h-1 w-full bg-gradient-to-r from-transparent via-brand-500/20 to-transparent"></div>
        </div>
        
    </div>

    <script>
        // Alpine.js component for authentication panel
        function authPanel() {
            return {
                // Mode: login, register, reset
                mode: 'login',
                
                // Reset password flow
                resetStep: 1,
                resetEmail: '',
                maskedEmail: '',
                otpDigits: ['', '', '', '', '', ''],
                resetToken: '',
                newPassword: '',
                confirmPassword: '',
                showPassword: false,
                
                // State
                isLoading: false,
                errorMessage: '',
                successMessage: '',
                countdown: 0,
                resendCooldown: 0,
                countdownInterval: null,
                resendInterval: null,
                
                // Computed password strength (0-4)
                get passwordStrength() {
                    const password = this.newPassword;
                    if (!password) return 0;
                    
                    let strength = 0;
                    if (password.length >= 8) strength++;
                    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                    if (/[0-9]/.test(password)) strength++;
                    if (/[^a-zA-Z0-9]/.test(password)) strength++;
                    
                    return strength;
                },
                
                // Switch between modes
                switchMode(newMode) {
                    this.errorMessage = '';
                    this.successMessage = '';
                    
                    if (newMode === 'login' || newMode === 'register') {
                        // Reset all reset-related state
                        this.resetStep = 1;
                        this.resetEmail = '';
                        this.otpDigits = ['', '', '', '', '', ''];
                        this.resetToken = '';
                        this.newPassword = '';
                        this.confirmPassword = '';
                        this.clearCountdown();
                    }
                    
                    this.mode = newMode;
                    
                    // Reinitialize icons after view change
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                },
                
                // Send OTP to email
                async sendOtp() {
                    if (!this.resetEmail) {
                        this.errorMessage = 'Email wajib diisi.';
                        return;
                    }
                    
                    this.isLoading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    
                    try {
                        const response = await fetch('{{ route("password.send-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                email: this.resetEmail
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.successMessage = data.message;
                            this.maskedEmail = data.email_masked;
                            this.countdown = data.expires_in || 600;
                            this.startCountdown();
                            this.startResendCooldown(60);
                            this.resetStep = 2;
                            
                            // Focus first OTP input
                            this.$nextTick(() => {
                                document.getElementById('otp-0')?.focus();
                                lucide.createIcons();
                            });
                        } else {
                            this.errorMessage = data.message;
                        }
                    } catch (error) {
                        this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        console.error('Send OTP error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                // Verify OTP code
                async verifyOtp() {
                    const otp = this.otpDigits.join('');
                    
                    if (otp.length !== 6) {
                        this.errorMessage = 'Masukkan kode OTP 6 digit.';
                        return;
                    }
                    
                    this.isLoading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    
                    try {
                        const response = await fetch('{{ route("password.verify-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                email: this.resetEmail,
                                otp: otp
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.successMessage = data.message;
                            this.resetToken = data.reset_token;
                            this.clearCountdown();
                            this.resetStep = 3;
                            
                            this.$nextTick(() => {
                                lucide.createIcons();
                            });
                        } else {
                            this.errorMessage = data.message;
                            // Clear OTP on error
                            this.otpDigits = ['', '', '', '', '', ''];
                            this.$nextTick(() => {
                                document.getElementById('otp-0')?.focus();
                            });
                        }
                    } catch (error) {
                        this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        console.error('Verify OTP error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                // Reset password
                async resetPassword() {
                    if (!this.newPassword || this.newPassword !== this.confirmPassword) {
                        this.errorMessage = 'Password tidak valid atau tidak cocok.';
                        return;
                    }
                    
                    if (this.passwordStrength < 2) {
                        this.errorMessage = 'Password terlalu lemah. Gunakan kombinasi huruf besar, huruf kecil, dan angka.';
                        return;
                    }
                    
                    this.isLoading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    
                    try {
                        const response = await fetch('{{ route("password.reset") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                email: this.resetEmail,
                                reset_token: this.resetToken,
                                password: this.newPassword,
                                password_confirmation: this.confirmPassword
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.successMessage = data.message;
                            
                            // Reset all state and switch to login after 2 seconds
                            setTimeout(() => {
                                this.switchMode('login');
                                this.successMessage = 'Password berhasil diperbarui. Silakan login dengan password baru.';
                            }, 2000);
                        } else {
                            this.errorMessage = data.message;
                        }
                    } catch (error) {
                        this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        console.error('Reset password error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                
                // Resend OTP
                async resendOtp() {
                    if (this.resendCooldown > 0) return;
                    
                    await this.sendOtp();
                },
                
                // OTP input handlers
                handleOtpInput(event, index) {
                    const value = event.target.value;
                    
                    // Only allow digits
                    if (!/^\d*$/.test(value)) {
                        this.otpDigits[index] = '';
                        return;
                    }
                    
                    // Move to next input
                    if (value && index < 5) {
                        document.getElementById('otp-' + (index + 1))?.focus();
                    }
                },
                
                handleOtpBackspace(event, index) {
                    // If current input is empty and backspace pressed, move to previous
                    if (!this.otpDigits[index] && index > 0) {
                        document.getElementById('otp-' + (index - 1))?.focus();
                    }
                },
                
                handleOtpPaste(event) {
                    event.preventDefault();
                    const pastedData = event.clipboardData.getData('text').trim();
                    
                    if (/^\d{6}$/.test(pastedData)) {
                        for (let i = 0; i < 6; i++) {
                            this.otpDigits[i] = pastedData[i];
                        }
                        document.getElementById('otp-5')?.focus();
                    }
                },
                
                // Countdown timer
                startCountdown() {
                    this.clearCountdown();
                    
                    this.countdownInterval = setInterval(() => {
                        if (this.countdown > 0) {
                            this.countdown--;
                        } else {
                            this.clearCountdown();
                        }
                    }, 1000);
                },
                
                clearCountdown() {
                    if (this.countdownInterval) {
                        clearInterval(this.countdownInterval);
                        this.countdownInterval = null;
                    }
                },
                
                formatCountdown() {
                    const minutes = Math.floor(this.countdown / 60);
                    const seconds = this.countdown % 60;
                    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                },
                
                // Resend cooldown
                startResendCooldown(seconds) {
                    this.resendCooldown = seconds;
                    
                    if (this.resendInterval) {
                        clearInterval(this.resendInterval);
                    }
                    
                    this.resendInterval = setInterval(() => {
                        if (this.resendCooldown > 0) {
                            this.resendCooldown--;
                        } else {
                            clearInterval(this.resendInterval);
                            this.resendInterval = null;
                        }
                    }, 1000);
                },
                
                // Cleanup on destroy
                destroy() {
                    this.clearCountdown();
                    if (this.resendInterval) {
                        clearInterval(this.resendInterval);
                    }
                }
            };
        }
        
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>