<footer class="bg-gray-50 pt-20 pb-10 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            <!-- Brand -->
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
                        {{ substr($siteSettings['site_name'] ?? 'B', 0, 1) }}
                    </div>
                    <span class="text-xl font-bold text-gray-900 tracking-tight">{{ $siteSettings['site_name'] ?? 'Portal' }}</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed">
                    {{ $siteSettings['site_tagline'] ?? 'Platform berita terkini dan terpercaya untuk Anda.' }}
                </p>
                <div class="flex gap-4">
                    @foreach(['facebook', 'twitter', 'instagram', 'youtube'] as $social)
                        @if($url = \App\Models\SiteSetting::get($social.'_url'))
                            <a href="{{ $url }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:scale-110 hover:border-indigo-500 hover:text-indigo-600 transition-all shadow-sm">
                                <i class="fab fa-{{ $social }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-bold text-gray-900 mb-6">Menu Utama</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('public.articles') }}" class="hover:text-indigo-600 transition-colors">Berita Terkini</a></li>
                    <li><a href="{{ route('public.gallery') }}" class="hover:text-indigo-600 transition-colors">Galeri Foto</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-bold text-gray-900 mb-6">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm text-gray-500">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ \App\Models\SiteSetting::get('site_address') ?? 'Alamat kantor belum diatur' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ \App\Models\SiteSetting::get('site_email') ?? 'email@example.com' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                         <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ \App\Models\SiteSetting::get('site_phone') ?? '+62 123 4567 890' }}</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter (Visual Only) -->
            <div>
                <h4 class="font-bold text-gray-900 mb-6">Berlangganan</h4>
                <p class="text-sm text-gray-500 mb-4">Dapatkan informasi terbaru langsung di inbox Anda.</p>
                <div class="flex gap-2">
                    <input type="email" placeholder="Email Anda" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all text-sm">
                    <button class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-500 font-medium">© {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'BTIKP' }}. All rights reserved.</p>
            <div class="flex gap-6 text-xs text-gray-400 font-medium">
                <a href="#" class="hover:text-gray-900">Privacy Policy</a>
                <a href="#" class="hover:text-gray-900">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
