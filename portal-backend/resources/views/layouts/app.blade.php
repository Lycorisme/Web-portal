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
    {{-- Scripts & Styles --}}
    @include('layouts.partials.head-scripts')
    @include('layouts.partials.styles')

    @stack('styles')
</head>

<body 
    x-data="appState()" 
    x-init="init()"
    :class="{ 'dark': darkMode }"
    class="font-jakarta bg-surface-50 dark:bg-surface-950 text-surface-900 dark:text-surface-100 transition-colors duration-300 overflow-x-hidden antialiased"
>

    {{-- Global Loading Screen - Rendered simultaneously with sidebar --}}
    @include('layouts.partials.loading-screen')

    {{-- Animated Background Elements --}}
    @include('layouts.partials.background')

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

    {{-- Custom Toast Notifications - Must load before footer-scripts --}}
    @include('layouts.partials.custom-toast')

    {{-- Initialize Scripts --}}
    @include('layouts.partials.footer-scripts')

    @stack('scripts')
</body>
</html>
