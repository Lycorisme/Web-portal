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
                            <p class="hidden sm:block text-sm text-surface-500 dark:text-surface-400 font-medium">
                                <span x-show="formMode === 'create'">Upload foto atau video kegiatan (maks 20 gambar)</span>
                                <span x-show="formMode === 'edit' || formMode === 'edit_group'">Edit informasi galeri</span>
                            </p>
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
                                <span x-text="formMode === 'create' ? (imageFiles.length > 1 ? 'Simpan ' + imageFiles.length + ' Gambar' : 'Simpan') : 'Update'"></span>
                                <div x-show="!formLoading && formData.title" class="absolute inset-0 -translate-x-[100%] group-hover/submit:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent z-10"></div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 scroll-smooth">
                    <form id="galleryForm" @submit.prevent="submitForm()" class="p-4 sm:p-6 space-y-6">
                        {{-- Media Type Selector (only in create mode) --}}
                        <div x-show="formMode === 'create'" class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50">
                            <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-3">
                                Tipe Media <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <button 
                                    type="button"
                                    @click="formData.media_type = 'image'; imageFiles = []; imagePreviews = [];"
                                    class="p-4 border-2 rounded-xl flex flex-col items-center gap-2 transition-all duration-300"
                                    :class="formData.media_type === 'image' 
                                        ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20 scale-[1.02] shadow-lg shadow-theme-500/10' 
                                        : 'border-surface-200 dark:border-surface-700 hover:border-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800'"
                                >
                                    <i data-lucide="images" class="w-8 h-8 transition-colors" :class="formData.media_type === 'image' ? 'text-theme-500' : 'text-surface-400'"></i>
                                    <span class="text-sm font-medium transition-colors" :class="formData.media_type === 'image' ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600 dark:text-surface-400'">Gambar</span>
                                    <span class="text-xs text-surface-400" x-show="formData.media_type === 'image'">Multiple upload</span>
                                </button>
                                <button 
                                    type="button"
                                    @click="formData.media_type = 'video'; imageFiles = []; imagePreviews = [];"
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
                                <span x-show="formMode === 'create' && imageFiles.length > 1" class="text-xs text-surface-400 font-normal ml-2">(akan ditambahkan nomor urut)</span>
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

                        {{-- Multiple Image Upload (for image type in create mode) --}}
                        <div x-show="formData.media_type === 'image'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                            <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-center flex-1 space-y-2">
                                        <div class="inline-flex items-center justify-center p-3 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-2xl mb-2">
                                            <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        </div>
                                        <h4 class="text-base font-bold text-surface-900 dark:text-white">
                                            <span x-show="formMode === 'create' || formMode === 'edit_group'">Upload Gambar (Multiple)</span>
                                            <span x-show="formMode === 'edit'">Ganti Gambar</span>
                                        </h4>
                                        <p class="text-xs text-surface-500">PNG, JPG, WEBP (Maks 10MB per file, maks 20 gambar)</p>
                                    </div>
                                    {{-- Image Counter --}}
                                    <div x-show="imagePreviews.length > 0" class="flex items-center gap-2">
                                        <span class="px-3 py-1.5 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-full text-sm font-semibold">
                                            <span x-text="imagePreviews.length"></span> gambar
                                        </span>
                                        <button 
                                            type="button" 
                                            @click="clearAllImages()"
                                            class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"
                                            title="Hapus semua"
                                        >
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Drop Zone --}}
                                <div 
                                    x-data="{ isDragging: false }"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="
                                        isDragging = false;
                                        const files = $event.dataTransfer.files;
                                        if (files.length) {
                                            if (formMode === 'edit') {
                                                handleSingleImageUpload({ target: { files: [files[0]] } });
                                            } else {
                                                handleMultipleImageUpload({ target: { files } });
                                            }
                                        }
                                    "
                                    class="relative w-full rounded-2xl border-3 border-dashed transition-all duration-500 ease-out overflow-hidden group"
                                    :class="isDragging 
                                        ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/10 scale-[1.02] shadow-2xl shadow-theme-500/10 ring-4 ring-theme-500/20' 
                                        : (formErrors.image || formErrors.images ? 'border-rose-400 bg-rose-50 dark:bg-rose-900/10' : 'border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700')"
                                >
                                    <input 
                                        type="file" 
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        accept="image/*"
                                        :multiple="formMode === 'create' || formMode === 'edit_group'"
                                        @change="formMode === 'edit' ? handleSingleImageUpload($event) : handleMultipleImageUpload($event)"
                                    >
                                    
                                    {{-- Empty State --}}
                                    <div x-show="imagePreviews.length === 0" class="py-12 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300 text-center p-4" :class="isDragging ? 'scale-110' : 'scale-100'">
                                        <div class="p-3 sm:p-4 rounded-full bg-white dark:bg-surface-700 shadow-sm mb-4">
                                            <i data-lucide="upload-cloud" class="w-6 h-6 sm:w-8 sm:h-8 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-surface-700 dark:text-surface-300">
                                            <span class="text-theme-600 dark:text-theme-400">Klik Upload</span> atau Drag & Drop
                                        </p>
                                        <p x-show="formMode === 'create' || formMode === 'edit_group'" class="text-xs text-surface-400 mt-1">Pilih beberapa gambar sekaligus</p>
                                    </div>

                                    {{-- Preview Grid --}}
                                    <div x-show="imagePreviews.length > 0" class="p-4">
                                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                            <template x-for="(preview, index) in imagePreviews" :key="index">
                                                <div class="relative aspect-square rounded-xl overflow-hidden group/item bg-surface-200 dark:bg-surface-700">
                                                    <img :src="preview.url" class="w-full h-full object-cover">
                                                    {{-- Remove Button --}}
                                                    <button 
                                                        type="button" 
                                                        @click.stop.prevent="removeImageAt(index)"
                                                        class="absolute top-2 right-2 p-1.5 bg-rose-500 text-white rounded-lg shadow-lg hover:bg-rose-600 transition-all z-20 opacity-0 group-hover/item:opacity-100 hover:scale-110"
                                                    >
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                    {{-- Index Badge --}}
                                                    <div class="absolute bottom-2 left-2 px-2 py-0.5 bg-black/50 text-white text-xs rounded-md font-medium">
                                                        <span x-text="index + 1"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            {{-- Add More Button (create/edit_group mode) --}}
                                            <div x-show="(formMode === 'create' || formMode === 'edit_group') && imagePreviews.length < 20" class="relative aspect-square rounded-xl border-2 border-dashed border-surface-300 dark:border-surface-600 flex items-center justify-center hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700 transition-all cursor-pointer group/add">
                                                <input 
                                                    type="file" 
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                    accept="image/*"
                                                    multiple
                                                    @change="handleMultipleImageUpload($event)"
                                                >
                                                <div class="flex flex-col items-center gap-1 pointer-events-none">
                                                    <i data-lucide="plus" class="w-6 h-6 text-surface-400 group-hover/add:text-theme-500 transition-colors"></i>
                                                    <span class="text-xs text-surface-400 group-hover/add:text-theme-500">Tambah</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <template x-if="formErrors.image || formErrors.images">
                                    <p class="text-center text-sm font-medium text-rose-500" x-text="formErrors.image ? formErrors.image[0] : formErrors.images[0]"></p>
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
                            {{-- Album with Autocomplete --}}
                            <div class="album-autocomplete relative">
                                <label class="block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-2">
                                    Album
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text"
                                        x-model="formData.album"
                                        @focus="showAlbumDropdown = true"
                                        @input="showAlbumDropdown = true"
                                        placeholder="Nama album/event"
                                        autocomplete="off"
                                        class="w-full px-4 py-3 pr-10 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                    >
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400">
                                        <i data-lucide="folder" class="w-4 h-4"></i>
                                    </div>
                                </div>
                                
                                {{-- Album Dropdown --}}
                                <div 
                                    x-show="showAlbumDropdown && filteredAlbums.length > 0"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-2"
                                    class="absolute z-30 left-0 right-0 mt-1 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl shadow-xl overflow-hidden max-h-48 overflow-y-auto"
                                >
                                    <template x-for="album in filteredAlbums" :key="album">
                                        <button 
                                            type="button"
                                            @click="selectAlbum(album)"
                                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors flex items-center gap-2"
                                            :class="formData.album === album ? 'bg-theme-50 dark:bg-theme-900/20 text-theme-600 dark:text-theme-400' : 'text-surface-700 dark:text-surface-300'"
                                        >
                                            <i data-lucide="folder" class="w-4 h-4 opacity-60"></i>
                                            <span x-text="album"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            {{-- Location --}}
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
