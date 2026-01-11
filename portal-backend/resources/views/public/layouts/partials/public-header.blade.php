{{-- Public Layout Header --}}
@php
    $siteAddress = \App\Models\SiteSetting::get('site_address', '');
    $sitePhone = \App\Models\SiteSetting::get('site_phone', '');
    $siteEmail = \App\Models\SiteSetting::get('site_email', '');
    $facebookUrl = \App\Models\SiteSetting::get('facebook_url', '');
    $instagramUrl = \App\Models\SiteSetting::get('instagram_url', '');
    $twitterUrl = \App\Models\SiteSetting::get('twitter_url', '');
    $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', '');
@endphp

<header class="header">
    <div class="header-top">
        <div class="header-top-content">
            <div>
                @if($sitePhone)
                    <i class="fas fa-phone"></i> {{ $sitePhone }}
                @endif
                @if($siteEmail)
                    <span style="margin-left: 1.5rem;">
                        <i class="fas fa-envelope"></i> {{ $siteEmail }}
                    </span>
                @endif
            </div>
            <div class="header-top-social">
                @if($facebookUrl)
                    <a href="{{ $facebookUrl }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if($instagramUrl)
                    <a href="{{ $instagramUrl }}" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                @endif
                @if($twitterUrl)
                    <a href="{{ $twitterUrl }}" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                @endif
                @if($youtubeUrl)
                    <a href="{{ $youtubeUrl }}" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="header-main">
        <a href="{{ route('public.home') }}" class="logo">
            @if(!empty($siteSettings['logo_url']))
                <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}">
            @endif
            <div class="logo-text">
                <span class="logo-name">{{ $siteSettings['site_name'] ?? 'BTIKP Portal' }}</span>
                <span class="logo-tagline">{{ $siteSettings['site_tagline'] ?? 'Portal Berita & Informasi' }}</span>
            </div>
        </a>

        <nav class="nav">
            <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('public.articles') }}" class="{{ request()->routeIs('public.articles') ? 'active' : '' }}">Artikel</a>
            <a href="{{ route('public.gallery') }}" class="{{ request()->routeIs('public.gallery') ? 'active' : '' }}">Galeri</a>
        </nav>

        <div class="header-actions">
            <button class="search-toggle" title="Cari">
                <i class="fas fa-search"></i>
            </button>

            @auth
                @if(auth()->user()->canAccessDashboard())
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('public.home') }}" class="btn btn-outline">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </a>
            @endauth

            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>
