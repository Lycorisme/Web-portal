{{-- Security Settings Tab --}}
<div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-surface-800 to-surface-900 flex items-center justify-center shadow-lg shadow-surface-800/30 flex-shrink-0">
                <i data-lucide="shield" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Pengaturan Keamanan</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Konfigurasi keamanan dan perlindungan portal</p>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-6">
            {{-- Rate Limit --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-accent-amber/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="gauge" class="w-5 sm:w-6 h-5 sm:h-6 text-accent-amber"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-surface-900 dark:text-white text-sm sm:text-base">Rate Limit per Menit</h3>
                        <p class="text-xs sm:text-sm text-surface-500">Batas request API per menit untuk setiap IP</p>
                    </div>
                </div>
                <input type="number" name="rate_limit_per_minute" id="rate_limit_per_minute"
                    value="{{ $rawSettings['rate_limit_per_minute'] ?? 60 }}"
                    min="10" max="1000"
                    class="w-full sm:w-24 px-4 py-2 bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-center text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
            </div>

            {{-- Auto Ban --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-accent-rose/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield-ban" class="w-5 sm:w-6 h-5 sm:h-6 text-accent-rose"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-surface-900 dark:text-white text-sm sm:text-base">Auto Ban IP Spam</h3>
                        <p class="text-xs sm:text-sm text-surface-500">Otomatis blokir IP yang melakukan spam</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="auto_ban_enabled" class="sr-only peer" {{ ($rawSettings['auto_ban_enabled'] ?? true) ? 'checked' : '' }}>
                    <div class="w-14 h-7 bg-surface-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-surface-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-surface-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-surface-600 peer-checked:bg-accent-emerald"></div>
                </label>
            </div>

            {{-- Maintenance Mode --}}
            <div class="flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <i data-lucide="wrench" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-surface-900 dark:text-white">Mode Pemeliharaan</h3>
                        <p class="text-sm text-surface-500">Aktifkan untuk menutup akses publik sementara</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="maintenance_mode" class="sr-only peer" {{ ($rawSettings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                    <div class="w-14 h-7 bg-surface-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-surface-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-surface-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-surface-600 peer-checked:bg-accent-amber"></div>
                </label>
            </div>

            {{-- Security Info Card --}}
            <div class="mt-6 sm:mt-8 p-4 sm:p-6 bg-gradient-to-br from-surface-900 to-surface-800 rounded-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-accent-emerald/20 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5 text-accent-emerald"></i>
                    </div>
                    <h3 class="font-bold text-white">Tips Keamanan</h3>
                </div>
                <ul class="space-y-2 text-sm text-surface-300">
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-accent-emerald"></i>
                        Gunakan rate limit tinggi (60-100) untuk traffic normal
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-accent-emerald"></i>
                        Aktifkan auto ban untuk perlindungan otomatis dari serangan DDoS
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-accent-emerald"></i>
                        Backup data secara berkala sebelum melakukan maintenance
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
