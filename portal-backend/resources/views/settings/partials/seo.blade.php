{{-- SEO Settings Tab --}}
<div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-cyan to-accent-emerald flex items-center justify-center shadow-lg shadow-accent-cyan/30 flex-shrink-0">
                <i data-lucide="search" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Pengaturan SEO</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Optimasi untuk mesin pencari Google</p>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-6">
            {{-- Meta Title --}}
            <div class="space-y-2">
                <label for="meta_title" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Meta Title
                </label>
                <input type="text" name="meta_title" id="meta_title"
                    value="{{ $rawSettings['meta_title'] ?? '' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-accent-cyan focus:border-transparent transition-all duration-200"
                    placeholder="Judul yang muncul di hasil pencarian Google">
                <p class="text-xs text-surface-400">Maksimal 60 karakter. Karakter saat ini: <span x-text="($el.previousElementSibling?.value?.length || 0)">0</span></p>
            </div>

            {{-- Meta Description --}}
            <div class="space-y-2">
                <label for="meta_description" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Meta Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="3"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-accent-cyan focus:border-transparent transition-all duration-200 resize-none"
                    placeholder="Deskripsi singkat yang muncul di bawah judul pada hasil pencarian">{{ $rawSettings['meta_description'] ?? '' }}</textarea>
                <p class="text-xs text-surface-400">Maksimal 160 karakter untuk hasil terbaik</p>
            </div>

            {{-- Meta Keywords --}}
            <div class="space-y-2">
                <label for="meta_keywords" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Meta Keywords
                </label>
                <input type="text" name="meta_keywords" id="meta_keywords"
                    value="{{ $rawSettings['meta_keywords'] ?? '' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-accent-cyan focus:border-transparent transition-all duration-200"
                    placeholder="berita, portal, informasi, terkini">
                <p class="text-xs text-surface-400">Pisahkan dengan koma</p>
            </div>

            {{-- Google Analytics --}}
            <div class="space-y-2">
                <label for="google_analytics_id" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Google Analytics ID
                </label>
                <div class="relative">
                    <i data-lucide="bar-chart-2" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input type="text" name="google_analytics_id" id="google_analytics_id"
                        value="{{ $rawSettings['google_analytics_id'] ?? '' }}"
                        class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-accent-cyan focus:border-transparent transition-all duration-200"
                        placeholder="UA-XXXXXXXXX-X atau G-XXXXXXXXXX">
                </div>
            </div>

            {{-- SEO Preview --}}
            <div class="mt-6 sm:mt-8 p-4 sm:p-6 bg-surface-100 dark:bg-surface-800 rounded-2xl">
                <h3 class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-4">Preview di Google</h3>
                <div class="space-y-1">
                    <p class="text-lg text-primary-600 dark:text-primary-400 font-medium truncate" x-text="$refs.meta_title?.value || 'Judul halaman Anda'">Judul halaman Anda</p>
                    <p class="text-sm text-accent-emerald truncate">https://example.com/</p>
                    <p class="text-sm text-surface-500 line-clamp-2" x-text="$refs.meta_description?.value || 'Deskripsi meta Anda akan muncul di sini. Ini membantu pengunjung memahami konten halaman sebelum mengklik.'">Deskripsi meta Anda akan muncul di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
