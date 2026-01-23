{{-- Kontak & Media Sosial Settings Tab --}}
<div x-show="activeTab === 'contact'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
    <div class="space-y-6">
        
        {{-- Informasi Kontak --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-teal-500/30 flex-shrink-0">
                    <i data-lucide="map-pin" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Informasi Kontak</h2>
                    <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Alamat, telepon, email, dan lokasi kantor</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- Alamat --}}
                <div class="md:col-span-2 space-y-2">
                    <label for="contact_site_address" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Alamat Kantor <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <i data-lucide="building-2" class="absolute left-4 top-3 w-5 h-5 text-surface-400"></i>
                        <textarea name="site_address" id="contact_site_address" rows="3"
                            class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota, Provinsi">{{ $rawSettings['site_address'] ?? '' }}</textarea>
                    </div>
                    <p class="text-xs text-surface-400">Alamat lengkap kantor yang akan ditampilkan di website</p>
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="contact_site_email" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Email Resmi
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-red-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <i data-lucide="mail" class="w-4 h-4 text-white"></i>
                        </div>
                        <input type="email" name="site_email" id="contact_site_email"
                            value="{{ $rawSettings['site_email'] ?? '' }}"
                            class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                            placeholder="email@instansi.go.id">
                    </div>
                </div>

                {{-- Telepon --}}
                <div class="space-y-2">
                    <label for="contact_site_phone" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="phone" class="w-4 h-4 text-white"></i>
                        </div>
                        <input type="text" name="site_phone" id="contact_site_phone"
                            value="{{ $rawSettings['site_phone'] ?? '' }}"
                            class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                            placeholder="+62 (021) 1234567">
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="space-y-2">
                    <label for="whatsapp_number" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nomor WhatsApp
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </div>
                        <input type="text" name="whatsapp_number" id="whatsapp_number"
                            value="{{ $rawSettings['whatsapp_number'] ?? '' }}"
                            class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                            placeholder="6281234567890">
                    </div>
                    <p class="text-xs text-surface-400">Format tanpa tanda + atau spasi, contoh: 6281234567890</p>
                </div>
            </div>
        </div>

        {{-- Sejarah & Visi Misi --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">


            {{-- Security Notice --}}
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-xl">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <i data-lucide="shield-check" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-amber-800 dark:text-amber-300">Perlindungan XSS</h4>
                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">Input teks akan divalidasi dan disanitasi secara otomatis untuk mencegah serangan Cross-Site Scripting (XSS). Tag HTML berbahaya akan dihapus.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Sejarah Singkat --}}
                <div class="space-y-2">
                    <label for="site_history" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Sejarah Singkat
                    </label>
                    <textarea name="site_history" id="site_history" rows="6"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-y"
                        placeholder="Ceritakan sejarah singkat organisasi Anda...">{{ $rawSettings['site_history'] ?? '' }}</textarea>
                    <p class="text-xs text-surface-400">Tulis dalam format teks biasa. Gunakan baris baru untuk paragraf baru.</p>
                </div>

                {{-- Visi & Misi --}}
                <div class="space-y-2">
                    <label for="site_vision_mission" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Visi & Misi
                    </label>
                    <textarea name="site_vision_mission" id="site_vision_mission" rows="8"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-y"
                        placeholder="VISI:
Menjadi...

MISI:
1. Melaksanakan...
2. Mengembangkan...
3. Meningkatkan...">{{ $rawSettings['site_vision_mission'] ?? '' }}</textarea>
                    <p class="text-xs text-surface-400">Gunakan format list dengan nomor atau tanda (-) untuk misi. Pisahkan visi dan misi dengan baris kosong.</p>
                </div>
            </div>
        </div>

        {{-- Google Maps --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">


            <div class="space-y-4">
                {{-- Instructions --}}
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-xl">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Cara mendapatkan kode embed</h4>
                            <ol class="text-xs text-blue-700 dark:text-blue-400 mt-2 space-y-1 list-decimal list-inside">
                                <li>Buka <a href="https://www.google.com/maps" target="_blank" class="underline hover:text-blue-900">Google Maps</a></li>
                                <li>Cari lokasi kantor Anda</li>
                                <li>Klik tombol "Bagikan" (Share)</li>
                                <li>Pilih tab "Sematkan peta" (Embed a map)</li>
                                <li>Salin seluruh kode HTML (dimulai dengan &lt;iframe...)</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Map Code Input --}}
                <div class="space-y-2">
                    <label for="site_map_code" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Kode Embed Google Maps
                    </label>
                    <textarea name="site_map_code" id="site_map_code" rows="4"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-y"
                        placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ $rawSettings['site_map_code'] ?? '' }}</textarea>
                </div>

                {{-- Map Preview --}}
                @if(!empty($rawSettings['site_map_code']))
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Preview Peta
                    </label>
                    <div class="w-full h-64 rounded-xl overflow-hidden border border-surface-200 dark:border-surface-700">
                        {!! $rawSettings['site_map_code'] !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Media Sosial --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-violet to-pink-500 flex items-center justify-center shadow-lg shadow-accent-violet/30 flex-shrink-0">
                    <i data-lucide="share-2" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Media Sosial</h2>
                    <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Hubungkan akun media sosial resmi organisasi</p>
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

                {{-- Twitter / X --}}
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

                {{-- TikTok --}}
                <div class="space-y-2">
                    <label for="tiktok_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        TikTok
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-surface-900 dark:bg-surface-700 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                        </div>
                        <input type="url" name="tiktok_url" id="tiktok_url"
                            value="{{ $rawSettings['tiktok_url'] ?? '' }}"
                            class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-surface-500 focus:border-transparent transition-all duration-200"
                            placeholder="https://tiktok.com/@yourhandle">
                    </div>
                </div>

                {{-- LinkedIn --}}
                <div class="space-y-2">
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
</div>
