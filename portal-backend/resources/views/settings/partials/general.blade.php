{{-- General Settings Tab --}}
<div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/30 flex-shrink-0">
                <i data-lucide="globe" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Pengaturan Umum</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Informasi dasar tentang portal berita Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            {{-- Site Name --}}
            <div class="space-y-2">
                <label for="site_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Nama Portal <span class="text-accent-rose">*</span>
                </label>
                <input type="text" name="site_name" id="site_name"
                    value="{{ $rawSettings['site_name'] ?? '' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                    placeholder="Contoh: Portal Berita BTIKP">
            </div>

            {{-- Tagline --}}
            <div class="space-y-2">
                <label for="site_tagline" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Tagline
                </label>
                <input type="text" name="site_tagline" id="site_tagline"
                    value="{{ $rawSettings['site_tagline'] ?? '' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                    placeholder="Contoh: Informasi Terkini dan Terpercaya">
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <label for="site_email" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Email Redaksi
                </label>
                <div class="relative">
                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input type="email" name="site_email" id="site_email"
                        value="{{ $rawSettings['site_email'] ?? '' }}"
                        class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="redaksi@example.com">
                </div>
            </div>

            {{-- Phone --}}
            <div class="space-y-2">
                <label for="site_phone" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Nomor Telepon
                </label>
                <div class="relative">
                    <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input type="text" name="site_phone" id="site_phone"
                        value="{{ $rawSettings['site_phone'] ?? '' }}"
                        class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="+62 xxx xxxx xxxx">
                </div>
            </div>

            {{-- Address --}}
            <div class="md:col-span-2 space-y-2">
                <label for="site_address" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Alamat Redaksi
                </label>
                <textarea name="site_address" id="site_address" rows="3"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 resize-none"
                    placeholder="Jl. Contoh No. 123, Kota, Provinsi">{{ $rawSettings['site_address'] ?? '' }}</textarea>
            </div>

            {{-- Description --}}
            <div class="md:col-span-2 space-y-2">
                <label for="site_description" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Deskripsi Website
                </label>
                <textarea name="site_description" id="site_description" rows="4"
                    placeholder="Deskripsi singkat tentang portal berita Anda...">{{ $rawSettings['site_description'] ?? '' }}</textarea>
            </div>

            {{-- Logo --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Logo Utama
                </label>
                <div class="relative group">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['logo_url']))
                            <img src="{{ $rawSettings['logo_url'] }}" alt="Logo" class="max-h-full max-w-full object-contain p-4">
                        @else
                            <i data-lucide="upload-cloud" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload logo</p>
                            <p class="text-xs text-surface-400">PNG, JPG, SVG (max 2MB)</p>
                        @endif
                    </div>
                    <input type="file" name="logo_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="logo_url_current" value="{{ $rawSettings['logo_url'] ?? '' }}">
                </div>
            </div>

            {{-- Favicon --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Favicon
                </label>
                <div class="relative group">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['favicon_url']))
                            <img src="{{ $rawSettings['favicon_url'] }}" alt="Favicon" class="w-16 h-16 object-contain">
                        @else
                            <i data-lucide="bookmark" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload favicon</p>
                            <p class="text-xs text-surface-400">ICO, PNG (32x32 atau 64x64)</p>
                        @endif
                    </div>
                    <input type="file" name="favicon_url" accept="image/*,.ico" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="favicon_url_current" value="{{ $rawSettings['favicon_url'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
