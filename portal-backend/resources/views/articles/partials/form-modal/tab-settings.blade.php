{{-- Tab 4: Settings (Category, Status, Featured) --}}
<div x-show="activeTab === 'settings'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-6">
            <div class="bg-surface-50 dark:bg-surface-800/50 p-6 rounded-2xl border border-surface-100 dark:border-surface-700">
                <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4"></i> Klasifikasi
                </h5>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Kategori</label>
                        <select x-model="formData.category_id" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                            <option value="">Pilih Kategori</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Waktu Baca</label>
                        <input type="number" x-model="formData.read_time" placeholder="Auto" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                    </div>
                </div>
            </div>

            <div class="bg-surface-50 dark:bg-surface-800/50 p-6 rounded-2xl border border-surface-100 dark:border-surface-700">
                <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="hash" class="w-4 h-4"></i> Label & Tags
                </h5>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Tags (Opsional)</label>
                        <div class="relative" x-data="{ open: false, search: '' }">
                            <div class="flex flex-wrap gap-2 p-2 min-h-[46px] bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl focus-within:ring-2 focus-within:ring-theme-500 focus-within:border-theme-500 transition-all cursor-text" @click="$refs.tagSearch.focus(); open = true">
                                <template x-for="tagId in formData.tag_ids" :key="tagId">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-theme-50 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 text-xs font-semibold rounded-lg border border-theme-100 dark:border-theme-900/50">
                                        <span x-text="tags.find(t => t.id == tagId)?.name"></span>
                                        <button type="button" @click.stop="formData.tag_ids = formData.tag_ids.filter(id => id != tagId)" class="hover:text-theme-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        </button>
                                    </span>
                                </template>
                                <input 
                                    x-ref="tagSearch"
                                    x-model="search"
                                    @focus="open = true"
                                    @keydown.backspace="if (search === '' && formData.tag_ids.length > 0) formData.tag_ids.pop()"
                                    @click.away="open = false"
                                    type="text" 
                                    placeholder="Cari tag..." 
                                    class="flex-1 min-w-[120px] bg-transparent border-none focus:ring-0 p-1 text-sm dark:text-white"
                                >
                            </div>

                            <div x-show="open && tags.filter(t => t.name.toLowerCase().includes(search.toLowerCase()) && !formData.tag_ids.includes(String(t.id))).length > 0" 
                                 class="absolute z-50 w-full mt-2 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl shadow-xl max-h-48 overflow-y-auto"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-cloak>
                                <div class="p-2 space-y-1">
                                    <template x-for="tag in tags.filter(t => t.name.toLowerCase().includes(search.toLowerCase()) && !formData.tag_ids.includes(String(t.id)))" :key="tag.id">
                                        <button 
                                            type="button"
                                            @click="formData.tag_ids.push(String(tag.id)); search = '';"
                                            class="w-full flex items-center px-3 py-2 text-sm text-left hover:bg-surface-100 dark:hover:bg-surface-700 rounded-lg transition-colors group dark:text-white"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-surface-400 group-hover:text-theme-500"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                            <span x-text="tag.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-[10px] text-surface-400">Pilih beberapa tag yang relevan dengan isi berita.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface-50 dark:bg-surface-800/50 p-6 rounded-2xl border border-surface-100 dark:border-surface-700">
                <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4"></i> Visibilitas
                </h5>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Status Publikasi</label>
                        <select x-model="formData.status" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                            <option value="draft">Draft (Konsep)</option>
                            <option value="pending">Pending Review</option>
                            <option value="published">Published (Terbit)</option>
                        </select>
                    </div>
                    <div x-show="formData.status === 'published'" x-transition>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Waktu Terbit</label>
                        <input type="datetime-local" x-model="formData.published_at" class="w-full px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm focus:ring-2 focus:ring-theme-500 focus:border-theme-500 transition-all dark:text-white">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-theme-50 to-white dark:from-theme-900/10 dark:to-surface-800 border border-theme-100 dark:border-theme-900/20 rounded-2xl p-6">
        <h5 class="text-sm font-bold text-surface-900 dark:text-white mb-4">Featured Options</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="relative flex items-start p-4 hover:bg-white/50 dark:hover:bg-surface-700/50 rounded-xl cursor-pointer transition-all border border-transparent hover:border-theme-200 dark:hover:border-theme-800">
                <div class="flex items-center h-5">
                    <input type="checkbox" x-model="formData.is_pinned" class="w-5 h-5 text-theme-600 rounded border-gray-300 focus:ring-theme-500">
                </div>
                <div class="ml-3 text-sm">
                    <span class="font-bold text-surface-900 dark:text-white">Pin to Home</span>
                    <p class="text-xs text-surface-500 mt-1">Sematkan berita ini di posisi paling atas halaman depan.</p>
                </div>
            </label>

            <label class="relative flex items-start p-4 hover:bg-white/50 dark:hover:bg-surface-700/50 rounded-xl cursor-pointer transition-all border border-transparent hover:border-theme-200 dark:hover:border-theme-800">
                <div class="flex items-center h-5">
                    <input type="checkbox" x-model="formData.is_headline" class="w-5 h-5 text-theme-600 rounded border-gray-300 focus:ring-theme-500">
                </div>
                <div class="ml-3 text-sm">
                    <span class="font-bold text-surface-900 dark:text-white">Jadikan Headline</span>
                    <p class="text-xs text-surface-500 mt-1">Tampilkan sebagai berita utama dengan layout khusus.</p>
                </div>
            </label>
        </div>
    </div>
</div>
