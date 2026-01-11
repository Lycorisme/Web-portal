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

    <style>
        :root {
            /* Primary Colors - Vibrant Blue Gradient */
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-200: #bfdbfe;
            --primary-300: #93c5fd;
            --primary-400: #60a5fa;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --primary-900: #1e3a8a;
            
            /* Accent Colors - Coral/Orange */
            --accent-50: #fff7ed;
            --accent-100: #ffedd5;
            --accent-200: #fed7aa;
            --accent-300: #fdba74;
            --accent-400: #fb923c;
            --accent-500: #f97316;
            --accent-600: #ea580c;
            --accent-700: #c2410c;
            
            /* Semantic Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            
            /* Neutrals */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-hero: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 50%, #db2777 100%);
            --gradient-card: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            
            /* Typography */
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --font-display: 'Playfair Display', serif;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-glow: 0 0 40px rgba(59, 130, 246, 0.3);
            
            /* Transitions */
            --transition-fast: 150ms ease;
            --transition-base: 200ms ease;
            --transition-slow: 300ms ease;
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .header {
            background: white;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-top {
            background: var(--gradient-hero);
            padding: 0.5rem 0;
        }

        .header-top-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .header-top a {
            color: white;
            text-decoration: none;
            transition: var(--transition-fast);
        }

        .header-top a:hover {
            color: var(--accent-300);
        }

        .header-top-social {
            display: flex;
            gap: 1rem;
        }

        .header-top-social a {
            font-size: 1rem;
        }

        .header-main {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo img {
            height: 48px;
            width: auto;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-name {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-700);
            line-height: 1.2;
        }

        .logo-tagline {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav {
            display: flex;
            gap: 0.25rem;
        }

        .nav a {
            padding: 0.625rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
            position: relative;
        }

        .nav a:hover,
        .nav a.active {
            color: var(--primary-600);
            background: var(--primary-50);
        }

        .nav a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: var(--radius-full);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-toggle {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: var(--radius-lg);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-base);
        }

        .search-toggle:hover {
            background: var(--primary-100);
            color: var(--primary-600);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-600);
            border: 2px solid var(--primary-200);
        }

        .btn-outline:hover {
            background: var(--primary-50);
            border-color: var(--primary-400);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: var(--radius-lg);
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--gray-900);
            color: white;
            margin-top: auto;
        }

        .footer-main {
            padding: 3rem 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 2rem;
        }

        .footer-brand p {
            color: var(--gray-400);
            margin-top: 1rem;
            font-size: 0.9375rem;
            line-height: 1.7;
        }

        .footer-title {
            font-size: 1rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1.25rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.9375rem;
            transition: var(--transition-fast);
        }

        .footer-links a:hover {
            color: var(--primary-400);
        }

        .footer-social {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-base);
        }

        .footer-social a:hover {
            background: var(--primary-600);
            transform: translateY(-2px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        /* ===== UTILITIES ===== */
        .section {
            padding: 3rem 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            position: relative;
            padding-left: 1rem;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient-primary);
            border-radius: var(--radius-full);
        }

        .view-all {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-600);
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: var(--transition-base);
        }

        .view-all:hover {
            color: var(--primary-700);
            gap: 0.75rem;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }

        .toast {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
            animation: slideIn 0.3s ease;
        }

        .toast.success {
            border-left: 4px solid var(--success);
        }

        .toast.error {
            border-left: 4px solid var(--danger);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Login Prompt Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-base);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: white;
            border-radius: var(--radius-2xl);
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.9);
            transition: var(--transition-base);
        }

        .modal-overlay.active .modal {
            transform: scale(1);
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            background: var(--primary-100);
            color: var(--primary-600);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1.25rem;
        }

        .modal h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .modal p {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
        }

        .modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .header-top {
                display: none;
            }

            .nav {
                display: none;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .header-actions .btn {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-social {
                justify-content: center;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .section-title {
                padding-left: 0;
            }

            .section-title::before {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $siteAddress = \App\Models\SiteSetting::get('site_address', '');
        $sitePhone = \App\Models\SiteSetting::get('site_phone', '');
        $siteEmail = \App\Models\SiteSetting::get('site_email', '');
        $facebookUrl = \App\Models\SiteSetting::get('facebook_url', '');
        $instagramUrl = \App\Models\SiteSetting::get('instagram_url', '');
        $twitterUrl = \App\Models\SiteSetting::get('twitter_url', '');
        $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', '');
        $footerText = \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' BTIKP. All rights reserved.');
    @endphp

    {{-- Header --}}
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

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
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
                            @php
                                $footerCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(5)->get();
                            @endphp
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

    {{-- Login Prompt Modal --}}
    <div class="modal-overlay" id="loginModal" x-data="{ open: false }" x-show="open" @keydown.escape.window="open = false" style="display: none;">
        <div class="modal" @click.away="open = false">
            <div class="modal-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h3>Login Diperlukan</h3>
            <p id="loginModalMessage">Silakan login terlebih dahulu untuk melakukan aksi ini.</p>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="closeLoginModal()">Batal</button>
                <a href="{{ route('login', ['intended' => url()->current()]) }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // Login Modal Functions
        function showLoginPrompt(message) {
            const modal = document.getElementById('loginModal');
            const msgEl = document.getElementById('loginModalMessage');
            msgEl.textContent = message || 'Silakan login terlebih dahulu untuk melakukan aksi ini.';
            modal.classList.add('active');
            modal.style.display = 'flex';
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 200);
        }

        // Toast Functions
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="color: var(--${type === 'success' ? 'success' : 'danger'});"></i>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Show flash messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    </script>

    @stack('scripts')
</body>
</html>
