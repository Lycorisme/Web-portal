<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Portal Berita dan Informasi BTIKP')">
    <meta name="keywords" content="@yield('meta_keywords', 'berita, informasi, BTIKP, portal')">
    
    <title>@yield('title', 'Beranda') - {{ $siteSettings['site_name'] ?? 'BTIKP Portal' }}</title>
    
    {{-- Favicon --}}
    @php
        $faviconUrl = \App\Models\SiteSetting::get('favicon_url', '');
    @endphp
    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Styles Partial --}}
    @include('public.layouts.partials.public-styles')

    @stack('styles')
</head>
<body>
    {{-- Header Partial --}}
    @include('public.layouts.partials.public-header')

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer Partial --}}
    @include('public.layouts.partials.public-footer')

    {{-- Scripts Partial (includes modals) --}}
    @include('public.layouts.partials.public-scripts')

    @stack('scripts')
</body>
</html>
