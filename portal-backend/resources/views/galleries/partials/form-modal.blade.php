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
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                {{-- Header --}}
                <div class="sticky top-0 z-10 bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <i :data-lucide="formMode === 'create' ? 'plus' : 'pencil'" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white" x-text="formMode === 'create' ? 'Tambah Galeri Baru' : 'Edit Galeri'"></h3>
                        </div>
                        <button @click="closeFormModal()" class="p-1.5 rounded-lg hover:bg-white/20 transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>

                {{-- Form Content --}}
                <form @submit.prevent="submitForm()" class="p-6 space-y-5">
                    {{-- Media Type Selector --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Tipe Media <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                type="button"
                                @click="formData.media_type = 'image'"
                                class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all"
                                :class="formData.media_type === 'image' 
                                    ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20' 
                                    : 'border-surface-200 dark:border-surface-700 hover:border-surface-300'"
                            >
                                <i data-lucide="image" class="w-8 h-8" :class="formData.media_type === 'image' ? 'text-theme-500' : 'text-surface-400'"></i>
                                <span class="text-sm font-medium" :class="formData.media_type === 'image' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Gambar</span>
                            </button>
                            <button 
                                type="button"
                                @click="formData.media_type = 'video'"
                                class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all"
                                :class="formData.media_type === 'video' 
                                    ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20' 
                                    : 'border-surface-200 dark:border-surface-700 hover:border-surface-300'"
                            >
                                <i data-lucide="video" class="w-8 h-8" :class="formData.media_type === 'video' ? 'text-theme-500' : 'text-surface-400'"></i>
                                <span class="text-sm font-medium" :class="formData.media_type === 'video' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Video</span>
                            </button>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Judul <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text"
                            x-model="formData.title"
                            placeholder="Masukkan judul galeri"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            :class="formErrors.title ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                        >
                        <template x-if="formErrors.title">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.title[0]"></p>
                        </template>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Deskripsi
                        </label>
                        <textarea 
                            x-model="formData.description"
                            rows="3"
                            placeholder="Deskripsi kegiatan (opsional)"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- Image Upload (for image type) --}}
                    <div x-show="formData.media_type === 'image'">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Gambar <span class="text-rose-500" x-text="formMode === 'create' ? '*' : ''"></span>
                        </label>
                        <div 
                            class="relative border-2 border-dashed rounded-xl p-6 text-center transition-all"
                            :class="formErrors.image ? 'border-rose-500 bg-rose-50 dark:bg-rose-900/10' : 'border-surface-300 dark:border-surface-600 hover:border-theme-400'"
                        >
                            <input 
                                type="file"
                                @change="handleImageUpload($event)"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            >
                            <template x-if="!imagePreview">
                                <div>
                                    <i data-lucide="upload-cloud" class="w-10 h-10 mx-auto mb-2 text-surface-400"></i>
                                    <p class="text-sm text-surface-600 dark:text-surface-400">
                                        <span class="text-theme-500 font-medium">Klik untuk upload</span> atau drag & drop
                                    </p>
                                    <p class="text-xs text-surface-400 mt-1">PNG, JPG, WEBP (Max 10MB)</p>
                                </div>
                            </template>
                            <template x-if="imagePreview">
                                <div class="relative">
                                    <img :src="imagePreview" class="max-h-48 mx-auto rounded-lg object-contain">
                                    <button 
                                        type="button"
                                        @click="removeImage()"
                                        class="absolute top-2 right-2 p-1.5 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors"
                                    >
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <template x-if="formErrors.image">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.image[0]"></p>
                        </template>
                    </div>

                    {{-- Video URL (for video type) --}}
                    <div x-show="formData.media_type === 'video'">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            URL Video <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="url"
                            x-model="formData.video_url"
                            placeholder="https://youtube.com/watch?v=..."
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            :class="formErrors.video_url ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                        >
                        <p class="mt-1 text-xs text-surface-500">Mendukung YouTube dan Vimeo</p>
                        <template x-if="formErrors.video_url">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.video_url[0]"></p>
                        </template>
                    </div>

                    {{-- Album & Location --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Album
                            </label>
                            <input 
                                type="text"
                                x-model="formData.album"
                                placeholder="Nama album/event"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Lokasi
                            </label>
                            <input 
                                type="text"
                                x-model="formData.location"
                                placeholder="Lokasi kegiatan"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Event Date --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Tanggal Kegiatan
                        </label>
                        <input 
                            type="date"
                            x-model="formData.event_date"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                        >
                    </div>

                    {{-- Status Toggles --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Is Published --}}
                        <div class="flex items-center gap-3 p-3 bg-surface-50 dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
                            <button 
                                type="button" 
                                @click="formData.is_published = !formData.is_published"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="formData.is_published ? 'bg-theme-500' : 'bg-surface-300 dark:bg-surface-600'"
                            >
                                <span 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="formData.is_published ? 'translate-x-5' : 'translate-x-0'"
                                ></span>
                            </button>
                            <div>
                                <span class="text-sm font-medium text-surface-700 dark:text-surface-300">
                                    <span x-text="formData.is_published ? 'Published' : 'Draft'"></span>
                                </span>
                                <p class="text-xs text-surface-500">Tampilkan di website</p>
                            </div>
                        </div>

                        {{-- Is Featured --}}
                        <div class="flex items-center gap-3 p-3 bg-surface-50 dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
                            <button 
                                type="button" 
                                @click="formData.is_featured = !formData.is_featured"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="formData.is_featured ? 'bg-amber-500' : 'bg-surface-300 dark:bg-surface-600'"
                            >
                                <span 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="formData.is_featured ? 'translate-x-5' : 'translate-x-0'"
                                ></span>
                            </button>
                            <div>
                                <span class="text-sm font-medium text-surface-700 dark:text-surface-300">
                                    <span x-text="formData.is_featured ? 'Featured' : 'Normal'"></span>
                                </span>
                                <p class="text-xs text-surface-500">Tampilkan sebagai unggulan</p>
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
                            <template x-if="formLoading">
                                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </template>
                            <span x-text="formMode === 'create' ? 'Simpan' : 'Update'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
