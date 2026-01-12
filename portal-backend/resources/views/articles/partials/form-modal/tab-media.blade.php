{{-- Tab 2: Media (Thumbnail Upload with Cropping) --}}
<div x-show="activeTab === 'media'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="bg-surface-50 dark:bg-surface-800/30 rounded-3xl p-4 sm:p-8 border border-surface-100 dark:border-surface-700/50 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-3 bg-theme-100 dark:bg-theme-900/30 text-theme-600 dark:text-theme-400 rounded-2xl mb-2">
                <i data-lucide="image-plus" class="w-8 h-8"></i>
            </div>
            <h4 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Assets Gambar</h4>
            <p class="text-xs sm:text-sm text-surface-500 max-w-sm mx-auto">Upload dan crop thumbnail berita untuk hasil yang optimal.</p>
        </div>

        {{-- Upload Area --}}
        <div 
            x-data="{ isDragging: false }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="
                isDragging = false;
                const file = $event.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    openCropModal(file);
                }
            "
            class="relative w-full aspect-video rounded-3xl border-3 border-dashed transition-all duration-500 ease-out overflow-hidden group"
            :class="isDragging 
                ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/10 scale-[1.02] shadow-2xl shadow-theme-500/10 ring-4 ring-theme-500/20' 
                : 'border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 hover:border-theme-400 hover:bg-surface-50 dark:hover:bg-surface-700'"
        >
            <input 
                type="file" 
                id="thumbnailInput"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                accept="image/jpeg,image/png,image/webp"
                @change="
                    const file = $event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        openCropModal(file);
                    }
                    $event.target.value = ''; // Reset input to allow same file selection
                "
            >
            
            {{-- Empty State --}}
            <template x-if="!formData.thumbnail_url">
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none transition-transform duration-300 text-center p-4" :class="isDragging ? 'scale-110' : 'scale-100'">
                    <div class="p-3 sm:p-4 rounded-full bg-white dark:bg-surface-700 shadow-sm mb-4">
                        <i data-lucide="upload-cloud" class="w-6 h-6 sm:w-8 sm:h-8 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
                    </div>
                    <p class="text-sm font-semibold text-surface-700 dark:text-surface-300">
                        <span class="text-theme-600 dark:text-theme-400">Klik Upload</span> / Drop
                    </p>
                    <p class="text-xs text-surface-400 mt-2">JPG, PNG, WebP • Rasio 16:9 akan diterapkan otomatis</p>
                </div>
            </template>

            {{-- Cropped Preview --}}
            <template x-if="formData.thumbnail_url">
                <div class="absolute inset-0 w-full h-full bg-black/5">
                    <img :src="formData.thumbnail_url" class="absolute inset-0 w-full h-full object-cover">
                    
                    {{-- Cropped Badge --}}
                    <div class="absolute top-4 left-4 px-3 py-1.5 bg-theme-500 text-white text-xs font-bold rounded-full flex items-center gap-1.5 shadow-lg">
                        <i data-lucide="crop" class="w-3.5 h-3.5"></i>
                        Cropped
                    </div>
                    
                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-6 text-white z-20 pointer-events-none">
                        <i data-lucide="refresh-cw" class="w-8 h-8 mb-2 drop-shadow-lg"></i>
                        <p class="text-sm font-medium">Klik untuk ganti gambar</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="absolute top-4 right-4 flex items-center gap-2 z-30 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                        {{-- Re-crop Button --}}
                        <button 
                            type="button" 
                            @click.stop.prevent="
                                if (originalImageFile) {
                                    openCropModal(originalImageFile);
                                }
                            "
                            x-show="originalImageFile"
                            class="p-2 bg-amber-500 text-white rounded-xl shadow-lg hover:bg-amber-600 transition-all hover:scale-110"
                            title="Crop Ulang"
                        >
                            <i data-lucide="crop" class="w-4 h-4"></i>
                        </button>
                        
                        {{-- Delete Button --}}
                        <button 
                            type="button" 
                            @click.stop.prevent="
                                formData.thumbnail = null; 
                                formData.thumbnail_url = '';
                                originalImageFile = null;
                            "
                            class="p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all hover:scale-110"
                            title="Hapus Gambar"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Error Message --}}
        <template x-if="formErrors.thumbnail">
            <p class="text-center text-sm font-medium text-rose-500" x-text="formErrors.thumbnail[0]"></p>
        </template>

        {{-- Aspect Ratio Info --}}
        <div class="flex items-center justify-center gap-4 text-xs text-surface-400">
            <div class="flex items-center gap-1.5">
                <i data-lucide="ratio" class="w-4 h-4"></i>
                <span>Rasio: 16:9</span>
            </div>
            <div class="flex items-center gap-1.5">
                <i data-lucide="maximize" class="w-4 h-4"></i>
                <span>Optimal: 1280 × 720 px</span>
            </div>
        </div>
    </div>
</div>

{{-- Image Cropper Modal --}}
<template x-teleport="body">
    <div 
        x-show="showCropModal" 
        x-cloak
        class="fixed inset-0 z-[9999] overflow-y-auto"
        aria-labelledby="crop-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showCropModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm"
        ></div>

        {{-- Modal Container --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div 
                x-show="showCropModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-4xl bg-white dark:bg-surface-900 rounded-3xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-theme-100 dark:bg-theme-900/30 rounded-xl">
                            <i data-lucide="crop" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white" id="crop-modal-title">Crop Thumbnail</h3>
                            <p class="text-xs text-surface-500">Pilih area gambar yang ingin ditampilkan (rasio 16:9)</p>
                        </div>
                    </div>
                    <button 
                        @click="closeCropModal()" 
                        class="p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-xl transition-colors"
                    >
                        <i data-lucide="x" class="w-5 h-5 text-surface-500"></i>
                    </button>
                </div>

                {{-- Crop Area --}}
                <div class="p-6 bg-surface-100 dark:bg-surface-800">
                    <div class="relative max-h-[60vh] overflow-hidden rounded-2xl bg-black">
                        <img 
                            id="cropperImage"
                            x-ref="cropperImage"
                            src=""
                            class="max-w-full max-h-[60vh] mx-auto block"
                            alt="Image to crop"
                        >
                    </div>
                </div>

                {{-- Controls --}}
                <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                    {{-- Zoom Slider --}}
                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-xs font-medium text-surface-500 w-16">Zoom</span>
                        <button type="button" @click="zoomCropper(-0.1)" class="p-1.5 hover:bg-surface-200 dark:hover:bg-surface-700 rounded-lg transition-colors">
                            <i data-lucide="zoom-out" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
                        </button>
                        <input 
                            type="range" 
                            min="0.1" 
                            max="3" 
                            step="0.1" 
                            x-model="cropZoom"
                            @input="setCropperZoom($event.target.value)"
                            class="flex-1 h-2 bg-surface-300 dark:bg-surface-600 rounded-full appearance-none cursor-pointer accent-theme-500"
                        >
                        <button type="button" @click="zoomCropper(0.1)" class="p-1.5 hover:bg-surface-200 dark:hover:bg-surface-700 rounded-lg transition-colors">
                            <i data-lucide="zoom-in" class="w-4 h-4 text-surface-600 dark:text-surface-400"></i>
                        </button>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button type="button" @click="rotateCropper(-90)" class="px-3 py-2 text-xs font-medium bg-surface-200 dark:bg-surface-700 hover:bg-surface-300 dark:hover:bg-surface-600 rounded-xl transition-colors flex items-center gap-1.5">
                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Putar Kiri</span>
                            </button>
                            <button type="button" @click="rotateCropper(90)" class="px-3 py-2 text-xs font-medium bg-surface-200 dark:bg-surface-700 hover:bg-surface-300 dark:hover:bg-surface-600 rounded-xl transition-colors flex items-center gap-1.5">
                                <i data-lucide="rotate-cw" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Putar Kanan</span>
                            </button>
                            <button type="button" @click="resetCropper()" class="px-3 py-2 text-xs font-medium bg-surface-200 dark:bg-surface-700 hover:bg-surface-300 dark:hover:bg-surface-600 rounded-xl transition-colors flex items-center gap-1.5">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Reset</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <button 
                                type="button"
                                @click="closeCropModal()"
                                class="px-4 py-2.5 text-sm font-semibold text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 border border-surface-300 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-700 rounded-xl transition-all"
                            >
                                Batal
                            </button>
                            <button 
                                type="button"
                                @click="applyCrop()"
                                class="px-5 py-2.5 text-sm font-semibold text-white bg-theme-600 hover:bg-theme-700 rounded-xl transition-all shadow-lg shadow-theme-500/20 flex items-center gap-2"
                            >
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Terapkan Crop
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
