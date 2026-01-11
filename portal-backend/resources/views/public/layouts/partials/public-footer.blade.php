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

<footer class="relative bg-slate-950 pt-24 pb-12 overflow-hidden border-t border-white/5">
    {{-- Background Gradients --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
            
            {{-- Brand Column --}}
            <div class="lg:col-span-4 space-y-6">
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-3">
                    @if(!empty($siteSettings['logo_url']))
                        <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}" class="h-12 w-auto brightness-0 invert">
                    @endif
                    <div class="flex flex-col">
                        <span class="font-display font-bold text-2xl text-white leading-none tracking-tight">
                            {{ $siteSettings['site_name'] ?? 'BTIKP' }}
                        </span>
                        <span class="text-[0.6rem] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Portal Berita
                        </span>
                    </div>
                </a>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    {{ $siteSettings['site_tagline'] ?? 'Portal Berita dan Informasi resmi dari BTIKP. Menyajikan berita terkini, artikel informatif, dan dokumentasi kegiatan.' }}
                </p>
                <div class="flex gap-4">
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 hover:border-blue-500 transition-all duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($instagramUrl)
                        <a href="{{ $instagramUrl }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-pink-600 hover:border-pink-500 transition-all duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($twitterUrl)
                        <a href="{{ $twitterUrl }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-sky-500 hover:border-sky-400 transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-600 hover:border-red-500 transition-all duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <div class="lg:col-span-2 lg:col-start-6">
                <h4 class="text-white font-bold mb-6 relative inline-block">
                    Navigasi
                    <span class="absolute -bottom-2 left-0 w-8 h-1 bg-emerald-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('public.home') }}" class="text-slate-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-slate-600"></i> Beranda</a></li>
                    <li><a href="{{ route('public.articles') }}" class="text-slate-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-slate-600"></i> Artikel</a></li>
                    <li><a href="{{ route('public.gallery') }}" class="text-slate-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-slate-600"></i> Galeri</a></li>
                </ul>
            </div>

            {{-- Categories --}}
            <div class="lg:col-span-3">
                <h4 class="text-white font-bold mb-6 relative inline-block">
                    Kategori
                    <span class="absolute -bottom-2 left-0 w-8 h-1 bg-blue-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3 text-sm">
                    @foreach($footerCategories as $cat)
                        <li>
                            <a href="{{ route('public.articles', ['kategori' => $cat->slug]) }}" class="text-slate-400 hover:text-blue-400 transition-colors flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700"></span>
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-3">
                <h4 class="text-white font-bold mb-6 relative inline-block">
                    Hubungi Kami
                    <span class="absolute -bottom-2 left-0 w-8 h-1 bg-purple-500 rounded-full"></span>
                </h4>
                <ul class="space-y-4 text-sm">
                    @if($siteAddress)
                        <li class="flex items-start gap-3 text-slate-400">
                            <i class="fas fa-map-marker-alt mt-1 text-purple-500"></i>
                            <span>{{ $siteAddress }}</span>
                        </li>
                    @endif
                    @if($sitePhone)
                        <li class="flex items-center gap-3 text-slate-400">
                            <i class="fas fa-phone text-purple-500"></i>
                            <span>{{ $sitePhone }}</span>
                        </li>
                    @endif
                    @if($siteEmail)
                        <li class="flex items-center gap-3 text-slate-400">
                            <i class="fas fa-envelope text-purple-500"></i>
                            <span>{{ $siteEmail }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-800 text-center">
            <p class="text-slate-500 text-sm">{!! $footerText !!}</p>
        </div>
    </div>
</footer>
