{{-- Photo Upload Modal --}}
<div x-show="showPhotoModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     x-cloak
     @click.self="showPhotoModal = false">
    
    <div x-show="showPhotoModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="w-full max-w-lg bg-white dark:bg-surface-900 rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden">
        
        {{-- Modal Header --}}
        <div class="relative bg-theme-gradient p-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Ubah Foto Profil</h3>
                        <p class="text-white/80 text-sm">Upload foto baru untuk profil Anda</p>
                    </div>
                </div>
                <button @click="showPhotoModal = false" 
                        class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-white"></i>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="p-6">
            <form id="photoUploadForm" @submit.prevent="uploadPhoto()">
                {{-- Drop Zone --}}
                <div class="relative group cursor-pointer mb-6"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="handleDrop($event)">
                    
                    <div class="border-2 border-dashed rounded-2xl transition-all duration-300 p-8"
                         :class="dragging 
                             ? 'border-theme-500 bg-theme-50 dark:bg-theme-900/20' 
                             : 'border-surface-300 dark:border-surface-700 hover:border-theme-500 bg-surface-50 dark:bg-surface-800/50'">
                        
                        {{-- Preview or Placeholder --}}
                        <div x-show="!previewUrl" class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
                                <i data-lucide="upload-cloud" class="w-10 h-10 text-surface-400"></i>
                            </div>
                            <p class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">
                                Drag & drop foto di sini
                            </p>
                            <p class="text-xs text-surface-500 dark:text-surface-400 mb-4">
                                atau klik untuk memilih file
                            </p>
                            <div class="flex flex-wrap justify-center gap-2 text-xs text-surface-400">
                                <span class="px-2 py-1 bg-surface-100 dark:bg-surface-800 rounded">JPG</span>
                                <span class="px-2 py-1 bg-surface-100 dark:bg-surface-800 rounded">PNG</span>
                                <span class="px-2 py-1 bg-surface-100 dark:bg-surface-800 rounded">GIF</span>
                                <span class="px-2 py-1 bg-surface-100 dark:bg-surface-800 rounded">WebP</span>
                                <span class="px-2 py-1 bg-surface-100 dark:bg-surface-800 rounded">Max 5MB</span>
                            </div>
                        </div>

                        {{-- Preview Image --}}
                        <div x-show="previewUrl" class="text-center" x-cloak>
                            <div class="w-40 h-40 mx-auto mb-4 rounded-2xl overflow-hidden ring-4 ring-theme-500 shadow-xl">
                                <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <p class="text-sm font-medium text-surface-700 dark:text-surface-300" x-text="selectedFileName"></p>
                            <p class="text-xs text-surface-500 dark:text-surface-400" x-text="selectedFileSize"></p>
                            <button type="button" 
                                    @click="clearPreview()"
                                    class="mt-3 text-sm text-accent-rose hover:underline">
                                Pilih foto lain
                            </button>
                        </div>
                    </div>

                    <input type="file" 
                           id="photoInput"
                           @change="handleFileSelect($event)"
                           accept="image/jpeg,image/png,image/gif,image/webp"
                           class="absolute inset-0 opacity-0 cursor-pointer">
                </div>

                {{-- Info Box --}}
                <div class="p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl mb-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-primary-700 dark:text-primary-300">
                            <p class="font-medium mb-1">Tips untuk foto terbaik:</p>
                            <ul class="list-disc list-inside text-xs space-y-1 text-primary-600 dark:text-primary-400">
                                <li>Gunakan foto dengan rasio 1:1 (persegi)</li>
                                <li>Pastikan wajah terlihat jelas</li>
                                <li>Hindari foto dengan latar belakang yang ramai</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button type="button" 
                            @click="showPhotoModal = false"
                            class="w-full sm:w-1/2 px-6 py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            :disabled="!selectedFile"
                            class="w-full sm:w-1/2 px-6 py-3 bg-theme-gradient text-white rounded-xl font-semibold shadow-theme hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-theme">
                        <i data-lucide="upload" class="w-4 h-4 inline mr-2"></i>
                        Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
