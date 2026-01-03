<!DOCTYPE html>
<html lang="id" class="scroll-smooth"
    x-data="{ sidebarOpen: true, darkMode: false, activeMenu: 'dashboard', showNotification: false, showProfile: false }"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Portal Berita BTIKP</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #3f3f46;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    {{-- Tailwind Config --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                        'space': ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        accent: {
                            cyan: '#06b6d4',
                            emerald: '#10b981',
                            amber: '#f59e0b',
                            rose: '#f43f5e',
                            violet: '#8b5cf6',
                        },
                        surface: {
                            50: '#fafafa',
                            100: '#f4f4f5',
                            200: '#e4e4e7',
                            800: '#27272a',
                            900: '#18181b',
                            950: '#0f0f11',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                        'fade-in': 'fadeIn 0.4s ease-out forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>

<body class="font-jakarta bg-surface-50 dark:bg-surface-950 text-surface-900 dark:text-surface-100 transition-colors duration-300 overflow-x-hidden antialiased">

    {{-- Animated Background Elements --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-primary-400/20 to-accent-violet/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute top-1/2 -left-40 w-80 h-80 bg-gradient-to-tr from-accent-cyan/15 to-accent-emerald/15 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-20 right-1/3 w-72 h-72 bg-gradient-to-tl from-accent-amber/10 to-accent-rose/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>

    {{-- Main Layout Container --}}
    <div class="relative z-10 flex min-h-screen">

        {{-- Sidebar Component --}}
        @include('partials.sidebar')

        {{-- Main Content Area --}}
        <main :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'" class="flex-1 transition-all duration-300 min-h-screen">

            {{-- Header Component --}}
            @include('partials.header')

            {{-- Page Content --}}
            <div class="p-4 lg:p-8">
                @yield('content')
            </div>

            {{-- Footer Component --}}
            @include('partials.footer')
        </main>
    </div>

    {{-- Toast Notifications --}}
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
                    'from-accent-emerald to-green-600': toast.type === 'success',
                    'from-accent-rose to-rose-600': toast.type === 'error',
                    'from-accent-amber to-orange-600': toast.type === 'warning',
                    'from-primary-500 to-primary-700': toast.type === 'info'
                }"
                class="bg-gradient-to-r text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[320px] backdrop-blur-sm border border-white/20"
            >
                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i :class="{
                        'check-circle': toast.type === 'success',
                        'x-circle': toast.type === 'error',
                        'alert-triangle': toast.type === 'warning',
                        'info': toast.type === 'info'
                    }" :data-lucide="toast.type === 'success' ? 'check-circle' : toast.type === 'error' ? 'x-circle' : toast.type === 'warning' ? 'alert-triangle' : 'info'" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-sm uppercase tracking-wide opacity-90" x-text="toast.title"></p>
                    <p class="text-white/90 text-sm" x-text="toast.message"></p>
                </div>
                <button @click="remove(toast.id)" class="hover:bg-white/20 p-2 rounded-full transition-all duration-150">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </template>
    </div>

    {{-- Alert/Confirm Dialog --}}
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
        {{-- Overlay --}}
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

        {{-- Dialog Box --}}
        <div 
            x-show="show"
            x-transition:enter="transition duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white dark:bg-surface-900 rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4"
        >
            <div class="flex flex-col items-center text-center">
                <div :class="{
                    'bg-accent-amber/20': type === 'warning',
                    'bg-accent-rose/20': type === 'danger',
                    'bg-primary-100 dark:bg-primary-900/30': type === 'info'
                }" class="w-20 h-20 rounded-full flex items-center justify-center mb-6">
                    <i :data-lucide="type === 'warning' ? 'alert-triangle' : type === 'danger' ? 'trash-2' : 'info'" 
                       :class="{
                           'text-accent-amber': type === 'warning',
                           'text-accent-rose': type === 'danger',
                           'text-primary-600': type === 'info'
                       }" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-bold text-surface-900 dark:text-white mb-3" x-text="title"></h3>
                <p class="text-surface-500 dark:text-surface-400 mb-8" x-text="message"></p>
                <div class="flex gap-4 w-full">
                    <button @click="cancel()" class="flex-1 px-6 py-3 bg-surface-100 dark:bg-surface-800 hover:bg-surface-200 dark:hover:bg-surface-700 text-surface-700 dark:text-surface-300 rounded-xl font-medium transition-all duration-200">
                        Batal
                    </button>
                    <button 
                        @click="confirm()" 
                        :class="{
                            'bg-accent-amber hover:bg-amber-600': type === 'warning',
                            'bg-accent-rose hover:bg-rose-600': type === 'danger',
                            'bg-primary-600 hover:bg-primary-700': type === 'info'
                        }"
                        class="flex-1 px-6 py-3 text-white rounded-xl font-medium transition-all duration-200"
                    >
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Initialize Lucide Icons --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });

        // Re-initialize icons after Alpine.js updates
        document.addEventListener('alpine:initialized', function () {
            setInterval(function () {
                lucide.createIcons();
            }, 500);
        });

        // Toast helper function
        function showToast(type, title, message) {
            window.dispatchEvent(new CustomEvent('toast', { 
                detail: { type, title, message } 
            }));
        }

        // Alert helper function
        function showAlert(type, title, message, callback) {
            window.dispatchEvent(new CustomEvent('alert', { 
                detail: { type, title, message, callback } 
            }));
        }
    </script>

    @stack('scripts')
</body>
</html>
