@php
    $siteName = $siteName ?? 'BTIKP Portal';
    $logoUrl = $logoUrl ?? '';
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - Access Point</title>
    
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
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/20">
                        <i data-lucide="layers" class="text-white w-7 h-7"></i>
                    </div>
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
                        <div class="w-2 h-2 rounded-full bg-accent-400 shadow-[0_0_10px_theme(colors.accent.400)]"></div>
                        <span class="text-[10px] font-bold tracking-widest uppercase text-slate-300 group-hover:text-white transition-colors">Real-time Sync</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- =======================
             RIGHT COLUMN (Dynamic)
             Strict width: 50%, Flex-none
        ======================== -->
        <div x-data="{ mode: 'login' }" class="flex-1 md:flex-none w-full md:w-[50%] relative bg-[#0b1120]/80 flex flex-col h-full">
            
            <!-- Navbar / Tabs -->
            <nav class="flex-none flex w-full border-b border-white/5">
                <button @click="mode = 'login'" 
                        class="flex-1 h-20 flex items-center justify-center text-sm font-semibold tracking-wide transition-all relative group"
                        :class="mode === 'login' ? 'text-white bg-white/[0.02]' : 'text-slate-500 hover:text-slate-300'">
                    Login Area
                    <div class="absolute bottom-0 w-full h-[2px] bg-brand-500 scale-x-0 transition-transform duration-300 origin-center"
                         :class="mode === 'login' ? 'scale-x-100' : 'scale-x-0'"></div>
                </button>
                <button @click="mode = 'register'" 
                        class="flex-1 h-20 flex items-center justify-center text-sm font-semibold tracking-wide transition-all relative group"
                        :class="mode === 'register' ? 'text-white bg-white/[0.02]' : 'text-slate-500 hover:text-slate-300'">
                    Registrasi
                    <div class="absolute bottom-0 w-full h-[2px] bg-brand-500 scale-x-0 transition-transform duration-300 origin-center"
                         :class="mode === 'register' ? 'scale-x-100' : 'scale-x-0'"></div>
                </button>
            </nav>

            <!-- Content Area: Independent Scroll -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-8 md:p-14 relative">
                
                <!-- Login View -->
                <div x-show="mode === 'login'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
                        <p class="text-slate-400">Masuk untuk mengakses dashboard Anda.</p>
                    </div>

                    <form action="#" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Identity</label>
                            <input type="email" 
                                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                                   placeholder="username@btikp.id">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Security Code</label>
                            <input type="password" 
                                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest" 
                                   placeholder="••••••••">
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="w-5 h-5 rounded border border-slate-600 bg-slate-800 flex items-center justify-center transition-colors group-hover:border-brand-500 relative">
                                    <input type="checkbox" class="absolute inset-0 opacity-0 cursor-pointer peer">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <span class="text-sm text-slate-400 group-hover:text-slate-300">Ingat Saya</span>
                            </label>
                            <a href="#" class="text-sm font-medium text-brand-400 hover:text-brand-300 transition-colors">Lupa Password?</a>
                        </div>

                        <button class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4">
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
                                    Saya menyetujui seluruh kebijakan privasi dan aturan keamanan data yang berlaku di BTIKP Portal.
                                </span>
                            </label>
                        </div>

                        <button class="w-full py-4 rounded-xl bg-slate-800 border border-white/10 hover:bg-white/5 text-white font-bold text-sm tracking-widest transition-all duration-300 uppercase mt-4">
                            Ajukan Pendaftaran
                        </button>
                    </form>
                </div>

            </div>
            
            <!-- Bottom decorative line -->
            <div class="h-1 w-full bg-gradient-to-r from-transparent via-brand-500/20 to-transparent"></div>
        </div>
        
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>