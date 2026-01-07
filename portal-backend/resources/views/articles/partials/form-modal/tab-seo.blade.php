{{-- Tab 3: SEO & Social Media --}}
<div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-6">
            <div>
                <h4 class="text-lg font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="search" class="w-5 h-5 text-theme-500"></i>
                    SEO Setup
                </h4>
                
                <div class="space-y-5">
                    <div class="group">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-1.5">Meta Title</label>
                        <input type="text" x-model="formData.meta_title" class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white" placeholder="Judul di hasil pencarian">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-1.5">Meta Description</label>
                        <textarea x-model="formData.meta_description" rows="4" class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all resize-none dark:text-white" placeholder="Deskripsi di hasil pencarian"></textarea>
                    </div>
                    <div class="group">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-1.5">Meta Keywords</label>
                        <input type="text" x-model="formData.meta_keywords" class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white" placeholder="pisahkan, dengan, koma">
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="pt-8 lg:pt-0">
            <div class="sticky top-4">
                <label class="block text-xs font-semibold uppercase tracking-wider text-surface-500 mb-3 text-center lg:text-left">Preview Social Media</label>
                
                {{-- Card Preview --}}
                <div class="bg-white dark:bg-surface-800 rounded-3xl overflow-hidden shadow-2xl border border-surface-100 dark:border-surface-700 transform transition-transform hover:scale-[1.02] duration-500">
                    <div class="h-48 bg-surface-100 dark:bg-surface-700 w-full relative overflow-hidden group">
                        <template x-if="formData.thumbnail_url">
                            <img :src="formData.thumbnail_url" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </template>
                        <template x-if="!formData.thumbnail_url">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-surface-400">
                                <i data-lucide="image" class="w-12 h-12 opacity-50 mb-2"></i>
                                <span class="text-xs">No Image</span>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                    </div>
                    <div class="p-6 relative">
                        <div class="absolute -top-10 right-6 w-12 h-12 bg-white dark:bg-surface-800 rounded-full flex items-center justify-center shadow-lg border-2 border-white dark:border-surface-700 overflow-hidden">
                            @php $logoUrl = \App\Models\SiteSetting::get('logo_url', ''); @endphp
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-bold text-theme-600">{{ strtoupper(Str::limit(config('app.name', 'APP'), 5, '')) }}</span>
                            @endif
                        </div>
                        <p class="text-[10px] items-center flex gap-1 text-surface-400 uppercase tracking-widest font-bold mb-2">
                            {{ parse_url(config('app.url', 'example.com'), PHP_URL_HOST) ?? config('app.name') }} <span class="w-1 h-1 rounded-full bg-surface-300"></span> News
                        </p>
                        <h3 class="font-bold text-lg text-surface-900 dark:text-white leading-tight mb-2 line-clamp-2" x-text="formData.meta_title || formData.title || 'Judul Berita Anda'"></h3>
                        <p class="text-sm text-surface-600 dark:text-surface-400 line-clamp-3 leading-relaxed" x-text="formData.meta_description || formData.excerpt || 'Deskripsi berita akan muncul disini...'">
                        </p>
                    </div>
                    <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-100 dark:border-surface-700/50 flex items-center justify-between text-xs text-surface-500">
                        <span>Baca Selengkapnya</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
