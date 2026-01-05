@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    {{-- Page Header --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-theme-gradient p-4 sm:p-6 lg:p-8 mb-4 sm:mb-8 animate-fade-in">
        <div class="absolute top-0 right-0 w-32 sm:w-64 h-32 sm:h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/4 w-16 sm:w-32 h-16 sm:h-32 bg-white/20 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 bg-white/20 backdrop-blur-sm rounded-full mb-3 sm:mb-4">
                <i data-lucide="settings" class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-white"></i>
                <span class="text-xs sm:text-sm text-white/90 font-medium">Konfigurasi Sistem</span>
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-1 sm:mb-2">Pengaturan Portal</h1>
            <p class="text-white/80 text-xs sm:text-sm lg:text-base max-w-xl">
                Kelola pengaturan umum, tampilan, SEO, media sosial, dan keamanan portal berita Anda.
            </p>
        </div>
    </div>

    {{-- Settings Tabs --}}
    <div x-data="{ activeTab: 'general' }" class="animate-slide-up" style="animation-delay: 0.1s;">
        {{-- Tab Navigation --}}
        {{-- Tab Navigation --}}
        <div class="mb-6 p-1.5 bg-white dark:bg-surface-900/50 rounded-2xl border border-surface-200/50 dark:border-surface-800/50 shadow-sm">
            <div class="flex items-center overflow-x-auto gap-2 p-0.5 scrollbar-hide">
                <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                    <span>Umum</span>
                </button>
                
                <button @click="activeTab = 'seo'"
                    :class="activeTab === 'seo' ? 'bg-gradient-to-r from-accent-cyan to-accent-emerald text-white shadow-lg shadow-accent-cyan/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>SEO</span>
                </button>

                <button @click="activeTab = 'social'"
                    :class="activeTab === 'social' ? 'bg-gradient-to-r from-accent-violet to-pink-500 text-white shadow-lg shadow-accent-violet/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                    <span>Sosial</span>
                </button>

                <button @click="activeTab = 'appearance'"
                    :class="activeTab === 'appearance' ? 'bg-gradient-to-r from-accent-amber to-accent-rose text-white shadow-lg shadow-accent-amber/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="palette" class="w-4 h-4"></i>
                    <span>Tampilan</span>
                </button>

                <button @click="activeTab = 'media'"
                    :class="activeTab === 'media' ? 'bg-gradient-to-r from-teal-500 to-accent-cyan text-white shadow-lg shadow-teal-500/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="image" class="w-4 h-4"></i>
                    <span>Media</span>
                </button>

                <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-gradient-to-r from-surface-800 to-surface-900 text-white shadow-lg shadow-surface-800/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                    class="flex-shrink-0 sm:flex-1 flex items-center justify-center gap-2 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl font-medium transition-all duration-200 text-sm whitespace-nowrap">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Keamanan</span>
                </button>
            </div>
        </div>

        <form id="settingsForm" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 resize-none"
                                placeholder="Deskripsi singkat tentang portal berita Anda...">{{ $rawSettings['site_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

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

            {{-- Social Media Settings Tab --}}
            <div x-show="activeTab === 'social'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                        <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-violet to-pink-500 flex items-center justify-center shadow-lg shadow-accent-violet/30 flex-shrink-0">
                            <i data-lucide="share-2" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Media Sosial</h2>
                            <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Hubungkan akun media sosial Anda</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        {{-- Facebook --}}
                        <div class="space-y-2">
                            <label for="facebook_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                Facebook
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="facebook" class="w-4 h-4 text-white"></i>
                                </div>
                                <input type="url" name="facebook_url" id="facebook_url"
                                    value="{{ $rawSettings['facebook_url'] ?? '' }}"
                                    class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="https://facebook.com/yourpage">
                            </div>
                        </div>

                        {{-- Twitter --}}
                        <div class="space-y-2">
                            <label for="twitter_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                Twitter / X
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-surface-900 dark:bg-surface-700 rounded-lg flex items-center justify-center">
                                    <i data-lucide="twitter" class="w-4 h-4 text-white"></i>
                                </div>
                                <input type="url" name="twitter_url" id="twitter_url"
                                    value="{{ $rawSettings['twitter_url'] ?? '' }}"
                                    class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-surface-500 focus:border-transparent transition-all duration-200"
                                    placeholder="https://twitter.com/yourhandle">
                            </div>
                        </div>

                        {{-- Instagram --}}
                        <div class="space-y-2">
                            <label for="instagram_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                Instagram
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 rounded-lg flex items-center justify-center">
                                    <i data-lucide="instagram" class="w-4 h-4 text-white"></i>
                                </div>
                                <input type="url" name="instagram_url" id="instagram_url"
                                    value="{{ $rawSettings['instagram_url'] ?? '' }}"
                                    class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all duration-200"
                                    placeholder="https://instagram.com/yourprofile">
                            </div>
                        </div>

                        {{-- YouTube --}}
                        <div class="space-y-2">
                            <label for="youtube_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                YouTube
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="youtube" class="w-4 h-4 text-white"></i>
                                </div>
                                <input type="url" name="youtube_url" id="youtube_url"
                                    value="{{ $rawSettings['youtube_url'] ?? '' }}"
                                    class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                    placeholder="https://youtube.com/c/yourchannel">
                            </div>
                        </div>

                        {{-- LinkedIn --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="linkedin_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                LinkedIn
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                                    <i data-lucide="linkedin" class="w-4 h-4 text-white"></i>
                                </div>
                                <input type="url" name="linkedin_url" id="linkedin_url"
                                    value="{{ $rawSettings['linkedin_url'] ?? '' }}"
                                    class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-200"
                                    placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appearance Settings Tab --}}
            <div x-show="activeTab === 'appearance'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                        <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-amber to-accent-rose flex items-center justify-center shadow-lg shadow-accent-amber/30 flex-shrink-0">
                            <i data-lucide="palette" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Theme Preset</h2>
                            <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pilih tema yang mempengaruhi tampilan button, select, hover, dan warna lainnya</p>
                        </div>
                    </div>

                    {{-- Theme Preset Grid --}}
                    <div x-data="{ selectedTheme: '{{ $rawSettings['current_theme'] ?? 'indigo' }}' }" class="space-y-4 sm:space-y-6">
                        <input type="hidden" name="current_theme" x-model="selectedTheme">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            {{-- Indigo Theme --}}
                            <label @click="selectedTheme = 'indigo'" 
                                :class="selectedTheme === 'indigo' ? 'ring-2 ring-primary-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-4 sm:p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="indigo" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5 flex-shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-indigo-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-indigo-300"></span>
                                            <span class="w-3 h-3 rounded-full bg-violet-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Indigo</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Profesional & Modern</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-indigo-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-indigo-300 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'indigo'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Emerald Theme --}}
                            <label @click="selectedTheme = 'emerald'" 
                                :class="selectedTheme === 'emerald' ? 'ring-2 ring-accent-emerald ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="emerald" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-teal-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-cyan-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Emerald</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Fresh & Natural</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-emerald-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-teal-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'emerald'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Rose Theme --}}
                            <label @click="selectedTheme = 'rose'" 
                                :class="selectedTheme === 'rose' ? 'ring-2 ring-rose-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="rose" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 shadow-lg shadow-rose-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-pink-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-fuchsia-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Rose</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Elegant & Bold</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-rose-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-pink-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'rose'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Amber Theme --}}
                            <label @click="selectedTheme = 'amber'" 
                                :class="selectedTheme === 'amber' ? 'ring-2 ring-amber-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="amber" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Amber</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Warm & Energetic</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-amber-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-orange-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'amber'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Cyan Theme --}}
                            <label @click="selectedTheme = 'cyan'" 
                                :class="selectedTheme === 'cyan' ? 'ring-2 ring-cyan-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="cyan" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 shadow-lg shadow-cyan-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-sky-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Cyan</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Cool & Calm</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-cyan-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-sky-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'cyan'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Violet Theme --}}
                            <label @click="selectedTheme = 'violet'" 
                                :class="selectedTheme === 'violet' ? 'ring-2 ring-violet-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="violet" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-violet-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-purple-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-fuchsia-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Violet</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Creative & Luxurious</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-violet-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-purple-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'violet'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Slate Theme --}}
                            <label @click="selectedTheme = 'slate'" 
                                :class="selectedTheme === 'slate' ? 'ring-2 ring-slate-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="slate" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 shadow-lg shadow-slate-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-slate-500"></span>
                                            <span class="w-3 h-3 rounded-full bg-gray-600"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Slate</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Minimal & Clean</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-slate-600 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-slate-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'slate'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Ocean Theme --}}
                            <label @click="selectedTheme = 'ocean'" 
                                :class="selectedTheme === 'ocean' ? 'ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="ocean" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 via-cyan-500 to-teal-500 shadow-lg shadow-blue-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Ocean</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Deep & Serene</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-teal-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'ocean'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>

                            {{-- Sunset Theme --}}
                            <label @click="selectedTheme = 'sunset'" 
                                :class="selectedTheme === 'sunset' ? 'ring-2 ring-orange-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                                class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                                <input type="radio" name="theme_preset" value="sunset" class="sr-only" x-model="selectedTheme">
                                <div class="flex items-start gap-4">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 via-rose-500 to-pink-600 shadow-lg shadow-orange-500/30"></div>
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Sunset</h3>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">Vibrant & Dynamic</p>
                                        <div class="mt-3 space-y-2">
                                            <div class="h-2 w-full bg-gradient-to-r from-orange-500 to-rose-500 rounded-full"></div>
                                            <div class="h-2 w-3/4 bg-pink-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedTheme === 'sunset'" class="absolute top-3 right-3">
                                    <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- Theme Info --}}
                        <div class="mt-6 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-200 dark:border-surface-700">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="info" class="w-5 h-5 text-primary-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-surface-900 dark:text-white mb-1">Tentang Theme Preset</h4>
                                    <p class="text-sm text-surface-500 dark:text-surface-400">
                                        Theme yang dipilih akan mempengaruhi warna button, select, hover state, link, badge, dan elemen UI lainnya di seluruh portal. 
                                        Perubahan akan diterapkan setelah Anda menyimpan pengaturan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Media Settings Tab --}}
            <div x-show="activeTab === 'media'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                        <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-teal-500 to-accent-cyan flex items-center justify-center shadow-lg shadow-teal-500/30 flex-shrink-0">
                            <i data-lucide="image" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Pengaturan Media</h2>
                            <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Logo, favicon, dan aset visual lainnya</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
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

                        {{-- Letterhead --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                Kop Surat
                            </label>
                            <div class="relative group">
                                <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                                    @if(!empty($rawSettings['letterhead_url']))
                                        <img src="{{ $rawSettings['letterhead_url'] }}" alt="Kop Surat" class="max-h-full max-w-full object-contain p-4">
                                    @else
                                        <i data-lucide="file-text" class="w-12 h-12 text-surface-400 mb-2"></i>
                                        <p class="text-sm text-surface-500">Klik untuk upload kop surat</p>
                                        <p class="text-xs text-surface-400">PNG, JPG (max 2MB)</p>
                                    @endif
                                </div>
                                <input type="file" name="letterhead_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                <input type="hidden" name="letterhead_url_current" value="{{ $rawSettings['letterhead_url'] ?? '' }}">
                            </div>
                        </div>

                        {{-- Signature --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                Tanda Tangan Digital
                            </label>
                            <div class="relative group">
                                <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                                    @if(!empty($rawSettings['signature_url']))
                                        <img src="{{ $rawSettings['signature_url'] }}" alt="Tanda Tangan" class="max-h-full max-w-full object-contain p-4">
                                    @else
                                        <i data-lucide="pen-tool" class="w-12 h-12 text-surface-400 mb-2"></i>
                                        <p class="text-sm text-surface-500">Klik untuk upload tanda tangan</p>
                                        <p class="text-xs text-surface-400">PNG dengan background transparan</p>
                                    @endif
                                </div>
                                <input type="file" name="signature_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                <input type="hidden" name="signature_url_current" value="{{ $rawSettings['signature_url'] ?? '' }}">
                            </div>
                        </div>

                        {{-- Stamp --}}
                        <div class="space-y-4 sm:col-span-2">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                                Stempel
                            </label>
                            <div class="relative group sm:max-w-md">
                                <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                                    @if(!empty($rawSettings['stamp_url']))
                                        <img src="{{ $rawSettings['stamp_url'] }}" alt="Stempel" class="max-h-full max-w-full object-contain p-4">
                                    @else
                                        <i data-lucide="stamp" class="w-12 h-12 text-surface-400 mb-2"></i>
                                        <p class="text-sm text-surface-500">Klik untuk upload stempel</p>
                                        <p class="text-xs text-surface-400">PNG dengan background transparan</p>
                                    @endif
                                </div>
                                <input type="file" name="stamp_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                <input type="hidden" name="stamp_url_current" value="{{ $rawSettings['stamp_url'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

                    <div class=\"space-y-4 sm:space-y-6\">
                        {{-- Rate Limit --}}
                        <div class=\"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl\">
                            <div class=\"flex items-center gap-3 sm:gap-4\">
                                <div class=\"w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-accent-amber/10 flex items-center justify-center flex-shrink-0\">
                                    <i data-lucide=\"gauge\" class=\"w-5 sm:w-6 h-5 sm:h-6 text-accent-amber\"></i>
                                </div>
                                <div>
                                    <h3 class=\"font-medium text-surface-900 dark:text-white text-sm sm:text-base\">Rate Limit per Menit</h3>
                                    <p class=\"text-xs sm:text-sm text-surface-500\">Batas request API per menit untuk setiap IP</p>
                                </div>
                            </div>
                            <input type=\"number\" name=\"rate_limit_per_minute\" id=\"rate_limit_per_minute\"
                                value=\"{{ $rawSettings['rate_limit_per_minute'] ?? 60 }}\"
                                min=\"10\" max=\"1000\"
                                class=\"w-full sm:w-24 px-4 py-2 bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-700 rounded-xl text-center text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200\">
                        </div>

                        {{-- Auto Ban --}}
                        <div class=\"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl\">
                            <div class=\"flex items-center gap-3 sm:gap-4\">
                                <div class=\"w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-accent-rose/10 flex items-center justify-center flex-shrink-0\">
                                    <i data-lucide=\"shield-ban\" class=\"w-5 sm:w-6 h-5 sm:h-6 text-accent-rose\"></i>
                                </div>
                                <div>
                                    <h3 class=\"font-medium text-surface-900 dark:text-white text-sm sm:text-base\">Auto Ban IP Spam</h3>
                                    <p class=\"text-xs sm:text-sm text-surface-500\">Otomatis blokir IP yang melakukan spam</p>
                                </div>
                            </div>
                            <label class=\"relative inline-flex items-center cursor-pointer\">
                                <input type=\"checkbox\" name=\"auto_ban_enabled\" class=\"sr-only peer\" {{ ($rawSettings['auto_ban_enabled'] ?? true) ? 'checked' : '' }}>
                                <div class=\"w-14 h-7 bg-surface-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-surface-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-surface-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-surface-600 peer-checked:bg-accent-emerald\"></div>
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

            {{-- Save Button --}}
            <div class="mt-6 sm:mt-8 flex flex-col-reverse sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                <button type="button" id="resetBtn"
                    class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200 text-sm sm:text-base">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-2"></i>
                    Reset
                </button>
                <button type="button" id="saveBtn"
                    class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-theme-gradient text-white rounded-xl font-semibold shadow-theme hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                    <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Store original form values for reset functionality
    const originalFormData = {};
    const form = document.getElementById('settingsForm');
    if (form) {
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            if (!(value instanceof File)) {
                originalFormData[key] = value;
            }
        }
    }
    
    // Store original saved theme
    const originalSavedTheme = '{{ $rawSettings['current_theme'] ?? 'indigo' }}';

    // Preview file upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = input.previousElementSibling;
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview" class="max-h-full max-w-full object-contain p-4">`;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Update theme preview in real-time when selection changes
    document.querySelectorAll('input[name="theme_preset"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedTheme = this.value;
            document.documentElement.setAttribute('data-theme', selectedTheme);
            // Also update the hidden input
            const hiddenInput = document.querySelector('input[name="current_theme"]');
            if (hiddenInput) {
                hiddenInput.value = selectedTheme;
            }
        });
    });

    // Function to update dynamic elements on the page
    function updateDynamicElements(settings) {
        // Update sidebar site name
        const sidebarSiteName = document.getElementById('sidebar-site-name');
        if (sidebarSiteName && settings.site_name) {
            sidebarSiteName.textContent = settings.site_name;
        }
        
        // Update sidebar tagline
        const sidebarTagline = document.getElementById('sidebar-site-tagline');
        if (sidebarTagline && settings.site_tagline !== undefined) {
            sidebarTagline.textContent = settings.site_tagline || '';
        }
        
        // Update sidebar footer name
        const sidebarFooterName = document.getElementById('sidebar-footer-name');
        if (sidebarFooterName && settings.site_name) {
            sidebarFooterName.textContent = settings.site_name;
        }
        
        // Update sidebar logo initial (if using initial letter)
        const sidebarLogoInitial = document.getElementById('sidebar-logo-initial');
        if (sidebarLogoInitial && settings.site_name) {
            sidebarLogoInitial.textContent = settings.site_name.charAt(0).toUpperCase();
        }
        
        // Update sidebar logo image
        const sidebarLogo = document.getElementById('sidebar-logo');
        if (sidebarLogo) {
            if (settings.logo_url) {
                // Replace with image logo
                sidebarLogo.innerHTML = `<img src="${settings.logo_url}" alt="${settings.site_name || 'Logo'}" class="w-full h-full object-cover">`;
                sidebarLogo.classList.remove('bg-theme-gradient');
                sidebarLogo.classList.add('overflow-hidden');
            } else if (settings.site_name) {
                // Revert to initials if no logo (though current UI doesn't allow removing logo easily, this handles the case)
                sidebarLogo.innerHTML = `<span id="sidebar-logo-initial" class="text-white font-space font-bold text-lg">${settings.site_name.charAt(0).toUpperCase()}</span>`;
                sidebarLogo.classList.add('bg-theme-gradient');
                sidebarLogo.classList.remove('overflow-hidden');
            }
        }
        
        // Update Favicon
        const faviconLink = document.getElementById('dynamic-favicon');
        if (faviconLink && settings.favicon_url) {
            faviconLink.href = settings.favicon_url;
        }
        
        // Update footer site name
        const footerSiteName = document.getElementById('footer-site-name');
        if (footerSiteName && settings.site_name) {
            footerSiteName.textContent = settings.site_name;
        }
        
        // Update footer email
        const footerEmail = document.getElementById('footer-email');
        if (footerEmail && settings.site_email !== undefined) {
            footerEmail.textContent = settings.site_email || '';
            footerEmail.href = settings.site_email ? `mailto:${settings.site_email}` : '#';
        }
        
        // Update theme
        if (settings.current_theme) {
            localStorage.setItem('themePreset', settings.current_theme);
            document.documentElement.setAttribute('data-theme', settings.current_theme);
        }
        
        // Update page title if site_name changed
        if (settings.site_name) {
            const pageTitle = document.querySelector('title');
            if (pageTitle) {
                const currentTitle = pageTitle.textContent;
                const parts = currentTitle.split(' - ');
                if (parts.length > 1) {
                    parts[parts.length - 1] = settings.site_name;
                    pageTitle.textContent = parts.join(' - ');
                }
            }
        }
    }

    // Save button with confirmation and AJAX submission
    document.getElementById('saveBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Simpan Pengaturan?',
            text: 'Pengaturan yang diubah akan diterapkan ke seluruh portal.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Get form data
                const form = document.getElementById('settingsForm');
                const formData = new FormData(form);
                
                // Sync theme to localStorage before submit
                const themeInput = document.querySelector('input[name="current_theme"]');
                if (themeInput) {
                    const selectedTheme = themeInput.value;
                    localStorage.setItem('themePreset', selectedTheme);
                    document.documentElement.setAttribute('data-theme', selectedTheme);
                }

                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update dynamic elements on the page
                        if (data.settings) {
                            updateDynamicElements(data.settings);
                            
                            // Update original form data for reset functionality
                            for (let key in data.settings) {
                                if (typeof data.settings[key] !== 'object') {
                                    originalFormData[key] = data.settings[key];
                                }
                            }
                        }
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Pengaturan berhasil disimpan!',
                            confirmButtonText: 'OK'
                        });
                        
                        // Reinitialize Lucide icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menyimpan pengaturan.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });

    // Reset button with confirmation
    document.getElementById('resetBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset Pengaturan?',
            text: 'Form akan dikembalikan ke nilai terakhir yang tersimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f59e0b',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Reset form values to original saved values
                const form = document.getElementById('settingsForm');
                
                // Reset text inputs and textareas
                for (let key in originalFormData) {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = originalFormData[key] === 'on' || originalFormData[key] === true || originalFormData[key] === '1';
                        } else if (input.type === 'radio') {
                            const radios = form.querySelectorAll(`[name="${key}"]`);
                            radios.forEach(radio => {
                                radio.checked = radio.value === originalFormData[key];
                            });
                        } else {
                            input.value = originalFormData[key];
                        }
                    }
                }
                
                // Reset file input previews to original images
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    const fieldName = input.name;
                    const currentValueInput = form.querySelector(`[name="${fieldName}_current"]`);
                    const container = input.previousElementSibling;
                    
                    if (currentValueInput && currentValueInput.value) {
                        container.innerHTML = `<img src="${currentValueInput.value}" alt="Preview" class="max-h-full max-w-full object-contain p-4">`;
                    }
                });
                
                // Reset theme preview to saved theme
                const savedTheme = originalFormData['current_theme'] || originalSavedTheme;
                document.documentElement.setAttribute('data-theme', savedTheme);
                
                // Update hidden input
                const hiddenInput = document.querySelector('input[name="current_theme"]');
                if (hiddenInput) {
                    hiddenInput.value = savedTheme;
                }
                
                // Update theme preset radio buttons
                const themeRadios = document.querySelectorAll('input[name="theme_preset"]');
                themeRadios.forEach(radio => {
                    radio.checked = radio.value === savedTheme;
                });
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                
                // Show toast notification
                showToast('success', 'Form telah direset ke nilai terakhir tersimpan');
            }
        });
    });
</script>
@endpush


