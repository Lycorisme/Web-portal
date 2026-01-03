{{-- Footer Component --}}
@php
    $siteName = \App\Models\SiteSetting::get('site_name', 'Portal Berita BTIKP');
    $siteEmail = \App\Models\SiteSetting::get('site_email', '');
@endphp
<footer class="mt-8 px-4 lg:px-8 py-6 border-t border-surface-200/50 dark:border-surface-800/50">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-surface-500 dark:text-surface-400">
        <p id="footer-copyright">© {{ date('Y') }} <span id="footer-site-name">{{ $siteName }}</span>. All rights reserved.</p>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-theme-600 transition-colors">Dokumentasi</a>
            <a href="#" class="hover:text-theme-600 transition-colors">Bantuan</a>
            <a href="#" class="hover:text-theme-600 transition-colors">Kebijakan</a>
            @if($siteEmail)
            <a href="mailto:{{ $siteEmail }}" id="footer-email" class="hover:text-theme-600 transition-colors">{{ $siteEmail }}</a>
            @endif
        </div>
    </div>
</footer>
