<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Portal News Redaksi</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'serif'],
                    },
                    colors: {
                        news: {
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                            red: '#dc2626',
                            blue: '#2563eb',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Sidebar Scrollbar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }
        
        /* Fast Animations */
        .animate-fade-in { animation: fadeIn 0.15s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.2s ease-out forwards; }
        .animate-slide-down { animation: slideDown 0.15s ease-out forwards; }
        .animate-slide-in-right { animation: slideInRight 0.2s ease-out forwards; }
        .animate-scale-in { animation: scaleIn 0.15s ease-out forwards; }
        
        @keyframes fadeIn { 
            0% { opacity: 0; } 
            100% { opacity: 1; } 
        }
        @keyframes fadeInUp { 
            0% { opacity: 0; transform: translateY(10px); } 
            100% { opacity: 1; transform: translateY(0); } 
        }
        @keyframes slideDown { 
            0% { opacity: 0; transform: translateY(-8px); } 
            100% { opacity: 1; transform: translateY(0); } 
        }
        @keyframes slideInRight { 
            0% { opacity: 0; transform: translateX(20px); } 
            100% { opacity: 1; transform: translateX(0); } 
        }
        @keyframes scaleIn { 
            0% { opacity: 0; transform: scale(0.95); } 
            100% { opacity: 1; transform: scale(1); } 
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false, dropdownOpen: false, notifOpen: false }">

    <!-- Mobile Header -->
    <div class="lg:hidden flex items-center justify-between bg-news-900 text-white p-4 sticky top-0 z-50 shadow-md">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded bg-news-red text-white font-serif font-bold">P</div>
            <span class="font-serif font-bold tracking-tight">PORTAL<span class="text-news-red">NEWS</span></span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-300 hover:text-white focus:outline-none transition-colors duration-150">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
            class="fixed inset-y-0 left-0 z-40 w-64 bg-news-900 text-white transition-transform duration-150 ease-out lg:static lg:translate-x-0 flex flex-col shadow-2xl"
        >
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-news-800 bg-news-900 sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded bg-news-red text-white font-serif font-bold text-lg shadow-lg">P</div>
                    <span class="text-xl font-bold tracking-tight font-serif">PORTAL<span class="text-news-red">NEWS</span></span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-3 space-y-1">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2">Overview</p>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-news-800 text-white border-l-4 border-news-red' : 'text-slate-300 hover:text-white hover:bg-news-800' }} rounded-lg group transition-all duration-150">
                    <i class="fa-solid fa-chart-line w-5 text-center {{ request()->routeIs('dashboard') ? 'text-news-red' : 'group-hover:text-blue-400' }}"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Redaksi</p>
                
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-regular fa-newspaper w-5 text-center group-hover:text-blue-400"></i>
                    <span class="font-medium text-sm">Artikel Berita</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-solid fa-layer-group w-5 text-center group-hover:text-blue-400"></i>
                    <span class="font-medium text-sm">Kategori & Tag</span>
                </a>
                
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-regular fa-images w-5 text-center group-hover:text-blue-400"></i>
                    <span class="font-medium text-sm">Galeri Media</span>
                </a>

                <p class="px-3 text-xs font-semibold text-news-red uppercase tracking-wider mb-2 mt-6 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-xs"></i> Security Center
                </p>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-solid fa-fingerprint w-5 text-center text-green-500"></i>
                    <span class="font-medium text-sm">Activity Logs</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-solid fa-ban w-5 text-center text-red-500"></i>
                    <span class="font-medium text-sm">Blocked IPs / Firewall</span>
                    <span class="ml-auto bg-red-600 text-white text-[10px] px-1.5 py-0.5 rounded-full">12</span>
                </a>

                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Sistem</p>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-solid fa-users-gear w-5 text-center group-hover:text-blue-400"></i>
                    <span class="font-medium text-sm">Manajemen Pengguna</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-news-800 rounded-lg group transition-all duration-150">
                    <i class="fa-solid fa-sliders w-5 text-center group-hover:text-blue-400"></i>
                    <span class="font-medium text-sm">Pengaturan Situs</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="border-t border-news-800 p-4 bg-news-900">
                <div class="flex items-center gap-3">
                    <img class="h-9 w-9 rounded-full object-cover border border-slate-600" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=2563eb&color=fff" alt="User">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->role ?? 'Super Admin' }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition-colors duration-150">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false" 
            x-transition:enter="transition-opacity duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 z-30 lg:hidden"
            x-cloak
        ></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">
            
            <!-- Header -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-20">
                <div class="relative w-64 hidden md:block">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari berita atau log..." class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-full text-sm focus:ring-2 focus:ring-news-blue focus:bg-white transition-all duration-150">
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-slate-500 hover:text-news-blue transition-colors duration-150">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 rounded-full border-2 border-white text-[10px] text-white flex items-center justify-center font-bold">3</span>
                        </button>

                        <!-- Notification Panel -->
                        <div 
                            x-show="open" 
                            @click.outside="open = false"
                            x-transition:enter="transition duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50"
                            x-cloak
                        >
                            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-white font-bold">Notifikasi</h3>
                                    <p class="text-slate-400 text-xs">3 belum dibaca</p>
                                </div>
                                <button class="text-xs text-slate-400 hover:text-white transition-colors px-2 py-1 hover:bg-white/10 rounded">
                                    Tandai dibaca
                                </button>
                            </div>

                            <div class="max-h-80 overflow-y-auto">
                                @include('partials.notification-item', ['type' => 'error', 'title' => 'Login Gagal (3x)', 'message' => 'IP: 192.168.1.45 mencoba masuk', 'time' => '2 menit lalu', 'unread' => true])
                                @include('partials.notification-item', ['type' => 'info', 'title' => 'Update Artikel', 'message' => "Artikel 'Pembangunan IKN' diperbarui", 'time' => '15 menit lalu', 'unread' => true])
                                @include('partials.notification-item', ['type' => 'success', 'title' => 'Login Berhasil', 'message' => 'User Super Admin masuk ke sistem', 'time' => '1 jam lalu', 'unread' => false])
                            </div>

                            <div class="p-3 bg-slate-50 border-t border-slate-100 flex justify-between">
                                <button class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">Hapus Semua</button>
                                <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">Lihat Semua →</a>
                            </div>
                        </div>
                    </div>

                    <div class="h-8 w-[1px] bg-slate-200 mx-1"></div>

                    <a href="http://localhost:3000" target="_blank" class="text-sm font-medium text-news-blue hover:text-news-900 flex items-center gap-2 transition-colors duration-150">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all duration-150">
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-slate-200" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=2563eb&color=fff" alt="User">
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-150" :class="open && 'rotate-180'"></i>
                        </button>

                        <div 
                            x-show="open" 
                            @click.outside="open = false"
                            x-transition:enter="transition duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50"
                            x-cloak
                        >
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                <p class="text-sm font-medium text-news-900">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <p class="text-xs text-slate-500">{{ Auth::user()->email ?? 'admin@portalnews.id' }}</p>
                            </div>
                            <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-150">
                                <i class="fa-solid fa-user text-slate-400 w-5"></i>
                                Profil Saya
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-150">
                                <i class="fa-solid fa-cog text-slate-400 w-5"></i>
                                Pengaturan
                            </a>
                            <hr class="border-slate-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                    <i class="fa-solid fa-sign-out-alt w-5"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-8">
                @yield('content')

                <!-- Footer -->
                <div class="mt-12 border-t border-slate-200 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-slate-400">
                    <p>&copy; 2025 Portal News Redaksi. All rights reserved.</p>
                    <div class="flex gap-4 mt-2 md:mt-0">
                        <span>Laravel v12</span>
                        <span>Next.js v15</span>
                        <span>Security Enabled</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notifications -->
    <div 
        x-data="{ 
            toasts: [],
            add(toast) {
                this.toasts.push({ id: Date.now(), ...toast });
                setTimeout(() => this.remove(this.toasts[0]?.id), 4000);
            },
            remove(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        }"
        @toast.window="add($event.detail)"
        class="fixed bottom-6 right-6 z-[100] space-y-3"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                x-show="true"
                x-transition:enter="transition duration-200"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition duration-150"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-4"
                :class="{
                    'from-emerald-500 to-green-600': toast.type === 'success',
                    'from-red-500 to-rose-600': toast.type === 'error',
                    'from-amber-500 to-orange-600': toast.type === 'warning',
                    'from-blue-500 to-indigo-600': toast.type === 'info'
                }"
                class="bg-gradient-to-r text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 min-w-[320px] backdrop-blur-sm border border-white/20"
            >
                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i :class="{
                        'fa-check-circle': toast.type === 'success',
                        'fa-times-circle': toast.type === 'error',
                        'fa-exclamation-triangle': toast.type === 'warning',
                        'fa-info-circle': toast.type === 'info'
                    }" class="fa-solid text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-sm uppercase tracking-wide opacity-90" x-text="toast.title"></p>
                    <p class="text-white/90 text-sm" x-text="toast.message"></p>
                </div>
                <button @click="remove(toast.id)" class="hover:bg-white/20 p-2 rounded-full transition-all duration-150">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Alert Dialog -->
    <div 
        x-data="{ 
            show: false,
            type: 'warning',
            title: '',
            message: '',
            callback: null,
            open(data) {
                this.type = data.type || 'warning';
                this.title = data.title;
                this.message = data.message;
                this.callback = data.callback;
                this.show = true;
            },
            confirm() {
                if (this.callback) this.callback();
                this.show = false;
            },
            cancel() {
                this.show = false;
            }
        }"
        @alert.window="open($event.detail)"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center"
    >
        <div 
            x-show="show"
            x-transition:enter="transition duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="cancel()"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        ></div>
        <div 
            x-show="show"
            x-transition:enter="transition duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4"
        >
            <div class="flex flex-col items-center text-center">
                <div :class="{
                    'bg-amber-100': type === 'warning',
                    'bg-red-100': type === 'danger',
                    'bg-blue-100': type === 'info'
                }" class="w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i :class="{
                        'fa-exclamation-triangle text-amber-600': type === 'warning',
                        'fa-trash-alt text-red-600': type === 'danger',
                        'fa-info-circle text-blue-600': type === 'info'
                    }" class="fa-solid text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2" x-text="title"></h3>
                <p class="text-slate-500 mb-6" x-text="message"></p>
                <div class="flex gap-3 w-full">
                    <button @click="cancel()" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-150">
                        Batal
                    </button>
                    <button 
                        @click="confirm()" 
                        :class="{
                            'bg-amber-600 hover:bg-amber-700': type === 'warning',
                            'bg-red-600 hover:bg-red-700': type === 'danger',
                            'bg-blue-600 hover:bg-blue-700': type === 'info'
                        }"
                        class="flex-1 px-4 py-3 text-white rounded-xl font-medium transition-all duration-150"
                    >
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
