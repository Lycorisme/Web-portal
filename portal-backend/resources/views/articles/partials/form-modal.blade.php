{{-- Form Modal (Create/Edit) --}}
<template x-teleport="body">
    <div 
        x-show="showFormModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showFormModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="closeFormModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showFormModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl max-h-[90vh] flex flex-col"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-4 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <i :data-lucide="formMode === 'create' ? 'plus' : 'pencil'" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white" x-text="formMode === 'create' ? 'Tambah Berita Baru' : 'Edit Berita'"></h3>
                        </div>
                        <button @click="closeFormModal()" class="p-1.5 rounded-lg hover:bg-white/20 transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>

                {{-- Form Content --}}
                <form @submit.prevent="submitForm()" class="p-6 space-y-5 overflow-y-auto flex-1">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Judul Berita <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text"
                            x-model="formData.title"
                            @input="generateSlug()"
                            placeholder="Masukkan judul berita"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            :class="formErrors.title ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                        >
                        <template x-if="formErrors.title">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.title[0]"></p>
                        </template>
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Slug
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400 text-sm">/berita/</span>
                            <input 
                                type="text"
                                x-model="formData.slug"
                                placeholder="url-berita"
                                class="w-full pl-20 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.slug ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                        </div>
                        <p class="mt-1 text-xs text-surface-500">Dibuat otomatis dari judul jika dikosongkan</p>
                        <template x-if="formErrors.slug">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.slug[0]"></p>
                        </template>
                    </div>

                    {{-- Category & Status Row --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Kategori
                            </label>
                            <select 
                                x-model="formData.category_id"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                            >
                                <option value="">Pilih Kategori</option>
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Status <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                x-model="formData.status"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.status ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                                <template x-for="opt in statusOptions" :key="opt.value">
                                    <option :value="opt.value" x-text="opt.label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Ringkasan
                        </label>
                        <textarea 
                            x-model="formData.excerpt"
                            placeholder="Ringkasan singkat berita (opsional)"
                            rows="2"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all resize-none"
                        ></textarea>
                        <p class="mt-1 text-xs text-surface-500" x-text="(formData.excerpt?.length || 0) + '/500 karakter'"></p>
                    </div>

                    {{-- Content --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Konten
                        </label>
                        <textarea 
                            x-model="formData.content"
                            placeholder="Tulis konten berita..."
                            rows="6"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- Thumbnail URL --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            URL Gambar Thumbnail
                        </label>
                        <div class="flex gap-3">
                            <input 
                                type="text"
                                x-model="formData.thumbnail"
                                placeholder="https://example.com/image.jpg"
                                class="flex-1 px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                            <div x-show="formData.thumbnail" class="w-16 h-12 rounded-lg overflow-hidden bg-surface-100 dark:bg-surface-800 flex-shrink-0">
                                <img :src="formData.thumbnail" alt="Preview" class="w-full h-full object-cover" onerror="this.style.display='none'">
                            </div>
                        </div>
                    </div>

                    {{-- Read Time --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Waktu Baca (menit)
                        </label>
                        <input 
                            type="number"
                            x-model="formData.read_time"
                            min="1"
                            placeholder="Otomatis dihitung"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                        >
                        <p class="mt-1 text-xs text-surface-500">Jika dikosongkan, akan dihitung otomatis berdasarkan konten</p>
                    </div>

                    {{-- SEO Section --}}
                    <div class="border-t border-surface-200 dark:border-surface-700 pt-5">
                        <h4 class="text-sm font-semibold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4 text-theme-500"></i>
                            SEO (Opsional)
                        </h4>
                        
                        <div class="space-y-4">
                            {{-- Meta Title --}}
                            <div>
                                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Meta Title</label>
                                <input 
                                    type="text"
                                    x-model="formData.meta_title"
                                    placeholder="Judul untuk mesin pencari"
                                    class="w-full px-3 py-2 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-lg text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                >
                            </div>

                            {{-- Meta Description --}}
                            <div>
                                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Meta Description</label>
                                <textarea 
                                    x-model="formData.meta_description"
                                    placeholder="Deskripsi untuk mesin pencari"
                                    rows="2"
                                    class="w-full px-3 py-2 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-lg text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all resize-none"
                                ></textarea>
                            </div>

                            {{-- Meta Keywords --}}
                            <div>
                                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Meta Keywords</label>
                                <input 
                                    type="text"
                                    x-model="formData.meta_keywords"
                                    placeholder="kata kunci, dipisahkan, koma"
                                    class="w-full px-3 py-2 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-lg text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-surface-200 dark:border-surface-700">
                        <button 
                            type="button"
                            @click="closeFormModal()"
                            class="flex-1 px-4 py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            :disabled="formLoading"
                            class="flex-1 px-4 py-3 bg-theme-gradient text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <div x-show="formLoading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span x-text="formMode === 'create' ? 'Simpan' : 'Update'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
