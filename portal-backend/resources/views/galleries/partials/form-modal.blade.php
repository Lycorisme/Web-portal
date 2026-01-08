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
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity"
            @click="closeFormModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-end sm:items-center justify-center sm:p-4">
            <div 
                x-show="showFormModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-2xl h-[90vh] sm:h-auto sm:max-h-[85vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-white/80 dark:bg-surface-900/80 backdrop-blur-md border-b border-surface-200/50 dark:border-surface-700/50 px-4 sm:px-6 py-4 flex-shrink-0 flex items-center justify-between z-20">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-theme-600 text-white flex items-center justify-center shadow-lg shadow-theme-500/20 shrink-0">
                            <i :data-lucide="formMode === 'create' ? 'plus' : 'pen-line'" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white tracking-tight leading-tight" x-text="formMode === 'create' ? 'Tambah Galeri Baru' : 'Edit Galeri'"></h3>
                            <p class="hidden sm:block text-sm text-surface-500 dark:text-surface-400 font-medium">Upload foto atau video kegiatan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button type="button" @click="closeFormModal()" class="px-3 py-2 sm:px-4 sm:py-2.5 text-xs sm:text-sm font-semibold text-surface-500 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-all">Batal</button>
                        
                        {{-- Submit Button --}}
                        <div class="relative group/submit">
                            <button 
                                type="submit" 
                                form="galleryForm"
                                :disabled="formLoading || !formData.title"
                                :class="{
                                    'bg-theme-600 hover:bg-theme-500 shadow-theme-500/30 hover:shadow-theme-500/50 hover:scale-[1.02]': !formLoading && formData.title,
                                    'bg-surface-400 dark:bg-surface-600 cursor-not-allowed': formLoading || !formData.title
                                }"
                                class="relative overflow-hidden px-4 py-2 sm:px-6 sm:py-2.5 text-white font-bold rounded-xl shadow-lg active:scale-95 transition-all duration-300 flex items-center justify-center gap-2 text-xs sm:text-sm"
                            >
                                <div x-show="formLoading" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                <span x-text="formMode === 'create' ? 'Simpan' : 'Update'"></span>
                                <div x-show="!formLoading && formData.title" class="absolute inset-0 -translate-x-[100%] group-hover/submit:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent z-10"></div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 scroll-smooth">
                    <form id="galleryForm" @submit.prevent="submitForm()" class="p-4 sm:p-6 space-y-6">
                        {{-- Media Type Selector --}}
                        <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50">
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-3">
                                Tipe Media <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <button 
                                    type="button"
                                    @click="formData.media_type = 'image'"
                                    class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all duration-300"
                                    :class="formData.media_type === 'image' 
                                        ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20 scale-[1.02] shadow-lg shadow-theme-500/10' 
                                        : 'border-surface-200 dark:border-surface-700 hover:border-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800'"
                                >
                                    <i data-lucide="image" class="w-8 h-8 transition-colors" :class="formData.media_type === 'image' ? 'text-theme-500' : 'text-surface-400'"></i>
                                    <span class="text-sm font-medium transition-colors" :class="formData.media_type === 'image' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Gambar</span>
                                </button>
                                <button 
                                    type="button"
                                    @click="formData.media_type = 'video'"
                                    class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all duration-300"
                                    :class="formData.media_type === 'video' 
                                        ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20 scale-[1.02] shadow-lg shadow-theme-500/10' 
                                        : 'border-surface-200 dark:border-surface-700 hover:border-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800'"
                                >
                                    <i data-lucide="video" class="w-8 h-8 transition-colors" :class="formData.media_type === 'video' ? 'text-theme-500' : 'text-surface-400'"></i>
                                    <span class="text-sm font-medium transition-colors" :class="formData.media_type === 'video' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Video</span>
                                </button>
                            </div>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
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
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
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
                        <div x-show="formData.media_type === 'image'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                            <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50 space-y-4">
                                <div class="text-center space-y-2">
                                    <div class="inline-flex items-center justify-center p-3 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-2xl mb-2">
                                        <i data-lucide="image-plus" class="w-6 h-6"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-surface-900 dark:text-white">Upload Gambar</h4>
                                    <p class="text-xs text-surface-500">PNG, JPG, WEBP (Maks 10MB)</p>
                                </div>

                                {{-- Drag & Drop Upload with Animation --}}
                                <div 
                                    x-data="{ isDragging: false }"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="
                                        isDragging = false;
                                        const file = $event.dataTransfer.files[0];
                                        if (file) {
                                            handleImageUpload({ target: { files: [file] } });
                                        }
                                    "
                                    class="relative w-full aspect-video rounded-2xl border-3 border-dashed transition-all duration-500 ease-out overflow-hidden group"
                                    :class="isDragging 
                                        ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/10 scale-[1.02] shadow-2xl shadow-theme-500/10 ring-4 ring-theme-500/20' 
                                        : (formErrors.image ? 'border-rose-400 bg-rose-50 dark:bg-rose-900/10' : 'border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700')"
                                >
                                    <input 
                                        type="file" 
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        accept="image/*"
                                        @change="handleImageUpload($event)"
                                    >
                                    
                                    {{-- Empty State --}}
                                    <template x-if="!imagePreview">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300 text-center p-4" :class="isDragging ? 'scale-110' : 'scale-100'">
                                            <div class="p-3 sm:p-4 rounded-full bg-white dark:bg-surface-700 shadow-sm mb-4">
                                                <i data-lucide="upload-cloud" class="w-6 h-6 sm:w-8 sm:h-8 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
                                            </div>
                                            <p class="text-sm font-semibold text-surface-700 dark:text-surface-300">
                                                <span class="text-theme-600 dark:text-theme-400">Klik Upload</span> / Drop
                                            </p>
                                        </div>
                                    </template>

                                    {{-- Preview with Animation --}}
                                    <template x-if="imagePreview">
                                        <div class="absolute inset-0 w-full h-full bg-black/5" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-110" x-transition:enter-end="opacity-100 scale-100">
                                            <img :src="imagePreview" class="absolute inset-0 w-full h-full object-cover">
                                            
                                            {{-- Hover Overlay --}}
                                            <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-6 text-white z-20 pointer-events-none">
                                                <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-lg"></i>
                                                <p class="text-sm font-medium">Klik untuk ganti</p>
                                            </div>

                                            <button 
                                                type="button" 
                                                @click.stop.prevent="removeImage()"
                                                class="absolute top-4 right-4 p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all z-30 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 hover:scale-110"
                                            >
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <template x-if="formErrors.image">
                                    <p class="text-center text-sm font-medium text-rose-500" x-text="formErrors.image[0]"></p>
                                </template>
                            </div>
                        </div>

                        {{-- Video URL (for video type) --}}
                        <div x-show="formData.media_type === 'video'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                            <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50 space-y-4">
                                <div class="text-center space-y-2">
                                    <div class="inline-flex items-center justify-center p-3 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-2xl mb-2">
                                        <i data-lucide="youtube" class="w-6 h-6"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-surface-900 dark:text-white">Link Video YouTube</h4>
                                    <p class="text-xs text-surface-500">Masukkan URL video dari YouTube</p>
                                </div>
                                <input 
                                    type="url"
                                    x-model="formData.video_url"
                                    placeholder="https://youtube.com/watch?v=... atau https://youtu.be/..."
                                    class="w-full px-4 py-3 bg-white dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                    :class="formErrors.video_url ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                                >
                                {{-- Video Preview --}}
                                <template x-if="formData.video_url && getYoutubeId(formData.video_url)">
                                    <div class="aspect-video rounded-xl overflow-hidden bg-black mt-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                        <img :src="'https://img.youtube.com/vi/' + getYoutubeId(formData.video_url) + '/maxresdefault.jpg'" class="w-full h-full object-cover">
                                    </div>
                                </template>
                                <template x-if="formErrors.video_url">
                                    <p class="text-sm font-medium text-rose-500" x-text="formErrors.video_url[0]"></p>
                                </template>
                            </div>
                        </div>

                        {{-- Album & Location --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
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
                                <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
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
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                                Tanggal Kegiatan
                            </label>
                            <input 
                                type="date"
                                x-model="formData.event_date"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>

                        {{-- Status Toggles --}}
                        <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50">
                            <h4 class="text-sm font-semibold text-surface-700 dark:text-surface-300 mb-4">Pengaturan</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Is Published --}}
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
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
                                        <span class="text-sm font-medium text-surface-700 dark:text-surface-300" x-text="formData.is_published ? 'Published' : 'Draft'"></span>
                                        <p class="text-xs text-surface-500">Tampilkan di website</p>
                                    </div>
                                </div>

                                {{-- Is Featured --}}
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
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
                                        <span class="text-sm font-medium text-surface-700 dark:text-surface-300" x-text="formData.is_featured ? 'Featured' : 'Normal'"></span>
                                        <p class="text-xs text-surface-500">Tampilkan sebagai unggulan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
