{{-- Public Layout Footer --}}
@php
    $siteAddress = \App\Models\SiteSetting::get('site_address', '');
    $sitePhone = \App\Models\SiteSetting::get('site_phone', '');
    $siteEmail = \App\Models\SiteSetting::get('site_email', '');
    $facebookUrl = \App\Models\SiteSetting::get('facebook_url', '');
    $instagramUrl = \App\Models\SiteSetting::get('instagram_url', '');
    $twitterUrl = \App\Models\SiteSetting::get('twitter_url', '');
    $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', '');
    $footerText = \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' BTIKP. All rights reserved.');
    $footerCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(5)->get();
@endphp

<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ route('public.home') }}" class="logo">
                        @if(!empty($siteSettings['logo_url']))
                            <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}" style="height: 48px; filter: brightness(0) invert(1);">
                        @endif
                        <span class="logo-name" style="color: white;">{{ $siteSettings['site_name'] ?? 'BTIKP Portal' }}</span>
                    </a>
                    <p>
                        {{ $siteSettings['site_tagline'] ?? 'Portal Berita dan Informasi resmi dari BTIKP. Menyajikan berita terkini, artikel informatif, dan dokumentasi kegiatan.' }}
                    </p>
                    <div class="footer-social">
                        @if($facebookUrl)
                            <a href="{{ $facebookUrl }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($instagramUrl)
                            <a href="{{ $instagramUrl }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($twitterUrl)
                            <a href="{{ $twitterUrl }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($youtubeUrl)
                            <a href="{{ $youtubeUrl }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('public.home') }}">Beranda</a></li>
                        <li><a href="{{ route('public.articles') }}">Artikel</a></li>
                        <li><a href="{{ route('public.gallery') }}">Galeri</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Kategori</h4>
                    <ul class="footer-links">
                        @foreach($footerCategories as $cat)
                            <li><a href="{{ route('public.articles', ['kategori' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Kontak</h4>
                    <ul class="footer-links">
                        @if($siteAddress)
                            <li><i class="fas fa-map-marker-alt" style="width: 16px; margin-right: 8px; color: var(--primary-400);"></i> {{ $siteAddress }}</li>
                        @endif
                        @if($sitePhone)
                            <li><i class="fas fa-phone" style="width: 16px; margin-right: 8px; color: var(--primary-400);"></i> {{ $sitePhone }}</li>
                        @endif
                        @if($siteEmail)
                            <li><i class="fas fa-envelope" style="width: 16px; margin-right: 8px; color: var(--primary-400);"></i> {{ $siteEmail }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            {!! $footerText !!}
        </div>
    </div>
</footer>
