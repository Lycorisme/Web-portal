<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Portal Berita BTIKP</title>
    <link rel="icon" id="dynamic-favicon" href="{{ \App\Models\SiteSetting::get('favicon_url', asset('favicon.ico')) }}">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    {{-- Initialize Dark Mode & Theme Before Page Render --}}
    <script>
        // Initialize dark mode from localStorage before page renders to prevent flash
        (function() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            const theme = localStorage.getItem('themePreset') || '{{ \App\Models\SiteSetting::get("current_theme", "indigo") }}';
            
            if (darkMode) {
                document.documentElement.classList.add('dark');
            }
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

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

        /* Hide scrollbar while keeping scroll functionality */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* ============================================
           GLOBAL THEME SYSTEM - CSS Variables
           ============================================ */
        
        :root {
            /* Default: Indigo Theme */
            --theme-50: 238 242 255;
            --theme-100: 224 231 255;
            --theme-200: 199 210 254;
            --theme-300: 165 180 252;
            --theme-400: 129 140 248;
            --theme-500: 99 102 241;
            --theme-600: 79 70 229;
            --theme-700: 67 56 202;
            --theme-800: 55 48 163;
            --theme-900: 49 46 129;
            --theme-gradient-from: #6366f1;
            --theme-gradient-to: #4f46e5;
        }

        /* Indigo Theme */
        [data-theme="indigo"] {
            --theme-50: 238 242 255;
            --theme-100: 224 231 255;
            --theme-200: 199 210 254;
            --theme-300: 165 180 252;
            --theme-400: 129 140 248;
            --theme-500: 99 102 241;
            --theme-600: 79 70 229;
            --theme-700: 67 56 202;
            --theme-800: 55 48 163;
            --theme-900: 49 46 129;
            --theme-gradient-from: #6366f1;
            --theme-gradient-to: #4f46e5;
        }

        /* Emerald Theme */
        [data-theme="emerald"] {
            --theme-50: 236 253 245;
            --theme-100: 209 250 229;
            --theme-200: 167 243 208;
            --theme-300: 110 231 183;
            --theme-400: 52 211 153;
            --theme-500: 16 185 129;
            --theme-600: 5 150 105;
            --theme-700: 4 120 87;
            --theme-800: 6 95 70;
            --theme-900: 6 78 59;
            --theme-gradient-from: #10b981;
            --theme-gradient-to: #059669;
        }

        /* Rose Theme */
        [data-theme="rose"] {
            --theme-50: 255 241 242;
            --theme-100: 255 228 230;
            --theme-200: 254 205 211;
            --theme-300: 253 164 175;
            --theme-400: 251 113 133;
            --theme-500: 244 63 94;
            --theme-600: 225 29 72;
            --theme-700: 190 18 60;
            --theme-800: 159 18 57;
            --theme-900: 136 19 55;
            --theme-gradient-from: #f43f5e;
            --theme-gradient-to: #e11d48;
        }

        /* Amber Theme */
        [data-theme="amber"] {
            --theme-50: 255 251 235;
            --theme-100: 254 243 199;
            --theme-200: 253 230 138;
            --theme-300: 252 211 77;
            --theme-400: 251 191 36;
            --theme-500: 245 158 11;
            --theme-600: 217 119 6;
            --theme-700: 180 83 9;
            --theme-800: 146 64 14;
            --theme-900: 120 53 15;
            --theme-gradient-from: #f59e0b;
            --theme-gradient-to: #d97706;
        }

        /* Cyan Theme */
        [data-theme="cyan"] {
            --theme-50: 236 254 255;
            --theme-100: 207 250 254;
            --theme-200: 165 243 252;
            --theme-300: 103 232 249;
            --theme-400: 34 211 238;
            --theme-500: 6 182 212;
            --theme-600: 8 145 178;
            --theme-700: 14 116 144;
            --theme-800: 21 94 117;
            --theme-900: 22 78 99;
            --theme-gradient-from: #06b6d4;
            --theme-gradient-to: #0891b2;
        }

        /* Violet Theme */
        [data-theme="violet"] {
            --theme-50: 245 243 255;
            --theme-100: 237 233 254;
            --theme-200: 221 214 254;
            --theme-300: 196 181 253;
            --theme-400: 167 139 250;
            --theme-500: 139 92 246;
            --theme-600: 124 58 237;
            --theme-700: 109 40 217;
            --theme-800: 91 33 182;
            --theme-900: 76 29 149;
            --theme-gradient-from: #8b5cf6;
            --theme-gradient-to: #7c3aed;
        }

        /* Slate Theme */
        [data-theme="slate"] {
            --theme-50: 248 250 252;
            --theme-100: 241 245 249;
            --theme-200: 226 232 240;
            --theme-300: 203 213 225;
            --theme-400: 148 163 184;
            --theme-500: 100 116 139;
            --theme-600: 71 85 105;
            --theme-700: 51 65 85;
            --theme-800: 30 41 59;
            --theme-900: 15 23 42;
            --theme-gradient-from: #64748b;
            --theme-gradient-to: #475569;
        }

        /* Ocean Theme */
        [data-theme="ocean"] {
            --theme-50: 239 246 255;
            --theme-100: 219 234 254;
            --theme-200: 191 219 254;
            --theme-300: 147 197 253;
            --theme-400: 96 165 250;
            --theme-500: 59 130 246;
            --theme-600: 37 99 235;
            --theme-700: 29 78 216;
            --theme-800: 30 64 175;
            --theme-900: 30 58 138;
            --theme-gradient-from: #3b82f6;
            --theme-gradient-to: #0891b2;
        }

        /* Sunset Theme */
        [data-theme="sunset"] {
            --theme-50: 255 247 237;
            --theme-100: 255 237 213;
            --theme-200: 254 215 170;
            --theme-300: 253 186 116;
            --theme-400: 251 146 60;
            --theme-500: 249 115 22;
            --theme-600: 234 88 12;
            --theme-700: 194 65 12;
            --theme-800: 154 52 18;
            --theme-900: 124 45 18;
            --theme-gradient-from: #f97316;
            --theme-gradient-to: #e11d48;
        }

        /* Theme-aware utility classes */
        .bg-theme-50 { background-color: rgb(var(--theme-50)); }
        .bg-theme-100 { background-color: rgb(var(--theme-100)); }
        .bg-theme-200 { background-color: rgb(var(--theme-200)); }
        .bg-theme-300 { background-color: rgb(var(--theme-300)); }
        .bg-theme-400 { background-color: rgb(var(--theme-400)); }
        .bg-theme-500 { background-color: rgb(var(--theme-500)); }
        .bg-theme-600 { background-color: rgb(var(--theme-600)); }
        .bg-theme-700 { background-color: rgb(var(--theme-700)); }
        .bg-theme-800 { background-color: rgb(var(--theme-800)); }
        .bg-theme-900 { background-color: rgb(var(--theme-900)); }

        .text-theme-50 { color: rgb(var(--theme-50)); }
        .text-theme-100 { color: rgb(var(--theme-100)); }
        .text-theme-200 { color: rgb(var(--theme-200)); }
        .text-theme-300 { color: rgb(var(--theme-300)); }
        .text-theme-400 { color: rgb(var(--theme-400)); }
        .text-theme-500 { color: rgb(var(--theme-500)); }
        .text-theme-600 { color: rgb(var(--theme-600)); }
        .text-theme-700 { color: rgb(var(--theme-700)); }
        .text-theme-800 { color: rgb(var(--theme-800)); }
        .text-theme-900 { color: rgb(var(--theme-900)); }

        .border-theme-500 { border-color: rgb(var(--theme-500)); }
        .border-theme-600 { border-color: rgb(var(--theme-600)); }

        .ring-theme-500 { --tw-ring-color: rgb(var(--theme-500)); }
        .ring-theme-600 { --tw-ring-color: rgb(var(--theme-600)); }

        .bg-theme-gradient {
            background: linear-gradient(135deg, var(--theme-gradient-from), var(--theme-gradient-to));
        }

        .shadow-theme {
            box-shadow: 0 10px 25px -3px rgb(var(--theme-500) / 0.3);
        }

        .hover\:bg-theme-600:hover { background-color: rgb(var(--theme-600)); }
        .hover\:bg-theme-700:hover { background-color: rgb(var(--theme-700)); }

        .focus\:ring-theme-500:focus { --tw-ring-color: rgb(var(--theme-500)); }

        /* Text Selection / Highlight */
        ::selection {
            background-color: rgb(var(--theme-500));
            color: white;
        }
        
        ::-moz-selection {
            background-color: rgb(var(--theme-500));
            color: white;
        }

        /* SweetAlert2 Custom Theme */
        .swal2-popup {
            border-radius: 1.5rem !important;
            padding: 2rem !important;
        }
        
        .dark .swal2-popup {
            background: #18181b !important;
            color: #f4f4f5 !important;
        }
        
        .dark .swal2-title {
            color: #ffffff !important;
        }
        
        .dark .swal2-html-container {
            color: #a1a1aa !important;
        }
        
        .swal2-confirm {
            background: linear-gradient(135deg, var(--theme-gradient-from), var(--theme-gradient-to)) !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            padding: 0.75rem 1.5rem !important;
        }
        
        .swal2-cancel {
            border-radius: 0.75rem !important;
            font-weight: 500 !important;
            padding: 0.75rem 1.5rem !important;
        }
        
        .dark .swal2-cancel {
            background: #27272a !important;
            color: #d4d4d8 !important;
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
                            300: '#d4d4d8',
                            400: '#a1a1aa',
                            500: '#71717a',
                            600: '#52525b',
                            700: '#3f3f46',
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

<body 
    x-data="appState()" 
    x-init="init()"
    :class="{ 'dark': darkMode }"
    class="font-jakarta bg-surface-50 dark:bg-surface-950 text-surface-900 dark:text-surface-100 transition-colors duration-300 overflow-x-hidden antialiased"
>

    {{-- Animated Background Elements --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-theme-gradient opacity-20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute top-1/2 -left-40 w-80 h-80 bg-gradient-to-tr from-accent-cyan/15 to-accent-emerald/15 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-20 right-1/3 w-72 h-72 bg-gradient-to-tl from-accent-amber/10 to-accent-rose/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>

    {{-- Main Layout Container --}}
    <div class="relative z-10 min-h-screen">

        {{-- Mobile Sidebar Backdrop --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
        </div>

        {{-- Sidebar Component --}}
        @include('partials.sidebar')

        {{-- Main Content Area --}}
        <main id="main-content" :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'" class="transition-all duration-300 min-h-screen pt-0">

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

    {{-- Initialize Scripts --}}
    <script>
        // App State Management
        function appState() {
            return {
                sidebarOpen: window.innerWidth >= 1024, // Closed on mobile, open on desktop
                darkMode: localStorage.getItem('darkMode') === 'true',
                themePreset: localStorage.getItem('themePreset') || '{{ \App\Models\SiteSetting::get("current_theme", "indigo") }}',
                showNotification: false,
                showProfile: false,
                currentPath: window.location.pathname,

                init() {
                    // Apply dark mode
                    this.applyDarkMode();
                    
                    // Apply theme
                    this.applyTheme();

                    // Listen for Livewire navigation
                    document.addEventListener('livewire:navigated', () => {
                        this.currentPath = window.location.pathname;
                    });

                    // Watch for dark mode changes
                    this.$watch('darkMode', (value) => {
                        localStorage.setItem('darkMode', value);
                        this.applyDarkMode();
                    });

                    // Watch for theme changes
                    this.$watch('themePreset', (value) => {
                        localStorage.setItem('themePreset', value);
                        this.applyTheme();
                    });
                },

                applyDarkMode() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },

                applyTheme() {
                    document.documentElement.setAttribute('data-theme', this.themePreset);
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                }
            }
        }

        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });

        // Re-initialize icons after Alpine.js updates
        document.addEventListener('alpine:initialized', function () {
            setInterval(function () {
                lucide.createIcons();
            }, 500);
        });

        // ============================================
        // SweetAlert2 Helper Functions
        // ============================================

        // Toast notification
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
            customClass: {
                popup: 'rounded-xl'
            }
        });

        // Show toast notification
        function showToast(type, title, message = '') {
            const iconMap = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info'
            };
            
            Toast.fire({
                icon: iconMap[type] || 'info',
                title: title,
                text: message
            });
        }

        // Show alert dialog
        function showAlert(type, title, message, callback = null) {
            const iconMap = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info',
                'danger': 'error',
                'question': 'question'
            };

            Swal.fire({
                icon: iconMap[type] || 'info',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // Show confirm dialog
        function showConfirm(title, message, callback, options = {}) {
            const isDark = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: title,
                text: message,
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // Show success message
        function showSuccess(title, message = '', callback = null) {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // Show error message
        function showError(title, message = '') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'swal2-confirm'
                }
            });
        }

        // Show loading
        function showLoading(title = 'Memproses...') {
            Swal.fire({
                title: title,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Close loading
        function closeLoading() {
            Swal.close();
        }

        // Show delete confirmation
        function showDeleteConfirm(itemName, callback) {
            Swal.fire({
                title: 'Hapus ' + itemName + '?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // ============================================
        // Flash Messages Handler
        // ============================================
        @if(session('success'))
            showToast('success', '{{ session('success') }}');
        @endif

        @if(session('error'))
            showToast('error', '{{ session('error') }}');
        @endif

        @if(session('warning'))
            showToast('warning', '{{ session('warning') }}');
        @endif

        @if(session('info'))
            showToast('info', '{{ session('info') }}');
        @endif
    </script>

    @stack('scripts')
</body>
</html>
