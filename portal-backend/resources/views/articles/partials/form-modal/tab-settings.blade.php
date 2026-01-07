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
