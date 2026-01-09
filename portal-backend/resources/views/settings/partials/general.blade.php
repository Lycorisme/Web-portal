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
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 resize-y"
                    placeholder="Deskripsi singkat tentang portal berita Anda...">{{ $rawSettings['site_description'] ?? '' }}</textarea>
            </div>

            {{-- Logo --}}
            <div class="space-y-4" x-data="{ isDragging: false, previewUrl: '{{ $rawSettings['logo_url'] ?? '' }}' }">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Logo Utama
                </label>
                <div 
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="
                        isDragging = false;
                        const file = $event.dataTransfer.files[0];
                        if (file) {
                            $refs.logoInput.files = $event.dataTransfer.files; 
                            previewUrl = URL.createObjectURL(file);
                        }
                    "
                    class="relative w-full h-48 rounded-2xl border-2 border-dashed transition-all duration-300 ease-out overflow-hidden group"
                    :class="isDragging 
                        ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/10 scale-[1.02] shadow-xl ring-4 ring-primary-500/10' 
                        : 'border-surface-300 dark:border-surface-700 bg-surface-50 dark:bg-surface-800/50 hover:border-primary-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                >
                    <input 
                        type="file" 
                        name="logo_url" 
                        x-ref="logoInput"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        accept="image/*"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                previewUrl = URL.createObjectURL(file);
                            }
                        "
                    >
                    <input type="hidden" name="logo_url_current" value="{{ $rawSettings['logo_url'] ?? '' }}">

                    {{-- Empty State --}}
                    <div x-show="!previewUrl" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300" :class="isDragging ? 'scale-110' : 'scale-100'">
                        <div class="p-3 bg-white dark:bg-surface-700 rounded-xl shadow-sm mb-3 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-surface-400 group-hover:text-primary-500 transition-colors"></i>
                        </div>
                        <p class="text-sm font-medium text-surface-600 dark:text-surface-300">Klik atau Drop Logo</p>
                        <p class="text-xs text-surface-400 mt-1">PNG, JPG, SVG (Max 2MB)</p>
                    </div>

                    {{-- Preview --}}
                    <div x-show="previewUrl" class="absolute inset-0 w-full h-full p-4 flex items-center justify-center bg-surface-100 dark:bg-surface-800">
                         <img :src="previewUrl" class="max-w-full max-h-full object-contain drop-shadow-sm transition-transform duration-500 group-hover:scale-105">
                         
                         {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center text-white z-20 pointer-events-none">
                            <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-md"></i>
                            <span class="text-xs font-medium bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">Ganti Logo</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Favicon --}}
            <div class="space-y-4" x-data="{ isDragging: false, previewUrl: '{{ $rawSettings['favicon_url'] ?? '' }}' }">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Favicon
                </label>
                <div 
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="
                        isDragging = false;
                        const file = $event.dataTransfer.files[0];
                        if (file) {
                             $refs.favInput.files = $event.dataTransfer.files;
                             previewUrl = URL.createObjectURL(file);
                        }
                    "
                    class="relative w-32 h-32 rounded-2xl border-2 border-dashed transition-all duration-300 ease-out overflow-hidden group"
                    :class="isDragging 
                        ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/10 scale-[1.02] shadow-xl ring-4 ring-primary-500/10' 
                        : 'border-surface-300 dark:border-surface-700 bg-surface-50 dark:bg-surface-800/50 hover:border-primary-400 hover:bg-surface-100 dark:hover:bg-surface-800'"
                >
                    <input 
                        type="file" 
                        name="favicon_url" 
                        x-ref="favInput"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        accept="image/*,.ico"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                previewUrl = URL.createObjectURL(file);
                            }
                        "
                    >
                    <input type="hidden" name="favicon_url_current" value="{{ $rawSettings['favicon_url'] ?? '' }}">

                    {{-- Empty State --}}
                    <div x-show="!previewUrl" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center p-2">
                        <i data-lucide="bookmark" class="w-6 h-6 text-surface-400 mb-2 group-hover:text-primary-500 transition-colors"></i>
                        <span class="text-xs text-surface-500">Upload</span>
                    </div>

                    {{-- Preview --}}
                    <div x-show="previewUrl" class="absolute inset-0 w-full h-full p-6 flex items-center justify-center bg-surface-100 dark:bg-surface-800">
                         <img :src="previewUrl" class="w-16 h-16 object-contain drop-shadow-sm transition-transform duration-500 group-hover:scale-110">
                         
                         {{-- Overlay --}}
                         <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-200 z-20"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
