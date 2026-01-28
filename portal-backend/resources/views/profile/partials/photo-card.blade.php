{{-- Profile Photo Card --}}
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col items-center text-center">
        {{-- Photo Container --}}
        <div class="relative group mb-4 sm:mb-6">
            <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 rounded-2xl sm:rounded-3xl overflow-hidden ring-4 transition-all duration-300 cursor-pointer"
                 :class="pendingPhotoFile ? 'ring-theme-500 ring-offset-2' : 'ring-surface-200 dark:ring-surface-700 group-hover:ring-theme-500'"
                 @click="$refs.photoInput.click()">
                @if($user->avatar)
                    <img id="profilePhotoPreview" 
                         src="{{ $user->avatar }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div id="profilePhotoPreview" class="w-full h-full bg-gradient-to-br from-theme-500 to-theme-600 flex items-center justify-center">
                        <span class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif

                
                {{-- Overlay on Hover --}}
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center rounded-2xl sm:rounded-3xl pointer-events-none">
                    <div class="text-white text-center">
                        <i data-lucide="camera" class="w-8 h-8 mx-auto mb-2"></i>
                        <span class="text-sm font-medium" x-text="pendingPhotoFile ? 'Ganti Foto' : 'Ubah Foto'"></span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons (visible on hover, always visible on mobile) --}}
            <div class="absolute top-2 right-2 flex items-center gap-1.5 z-30 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300"
                 x-show="hasPhoto || pendingPhotoFile">
                {{-- Edit/Crop Button --}}
                <button 
                    type="button" 
                    @click.stop.prevent="openCropModal()"
                    class="p-2 bg-cyan-500 text-white rounded-xl shadow-lg hover:bg-cyan-600 transition-all hover:scale-110"
                    title="Crop Foto"
                >
                    <i data-lucide="crop" class="w-4 h-4"></i>
                </button>
                
                {{-- Delete Button --}}
                <button 
                    type="button" 
                    @click.stop.prevent="deletePhoto()"
                    class="p-2 bg-rose-500 text-white rounded-xl shadow-lg hover:bg-rose-600 transition-all hover:scale-110"
                    title="Hapus Foto"
                >
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Status Badge - Changes based on state --}}
            <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white dark:border-surface-900 flex items-center justify-center transition-colors duration-300"
                 :class="pendingPhotoFile ? 'bg-theme-500' : 'bg-accent-emerald'">
                <i :data-lucide="pendingPhotoFile ? 'upload' : 'check'" class="w-4 h-4 text-white"></i>
            </div>

            {{-- Upload Progress Indicator --}}
            <div x-show="isUploading" 
                 x-cloak
                 class="absolute inset-0 bg-black/60 rounded-2xl sm:rounded-3xl flex items-center justify-center">
                <div class="text-white text-center">
                    <i data-lucide="loader-2" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                    <span class="text-sm font-medium">Mengupload...</span>
                </div>
            </div>
        </div>


        {{-- Name & Role --}}
        <h2 class="text-xl sm:text-2xl font-bold text-surface-900 dark:text-white mb-1">
            {{ $user->name }}
        </h2>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-theme-100 dark:bg-theme-900/30 rounded-full mb-3">
            <i data-lucide="shield" class="w-4 h-4 text-theme-600 dark:text-theme-400"></i>
            <span class="text-sm font-medium text-theme-600 dark:text-theme-400">
                @switch($user->role)
                    @case('super_admin')
                        {{-- HIDDEN SUPER ADMIN: Display as generic Administrator --}}
                        Administrator
                        @break
                    @case('admin')
                        Administrator
                        @break
                    @default
                        Editor
                @endswitch
            </span>
        </div>
        
        {{-- Contact Info --}}
        <div class="w-full space-y-2 text-sm text-surface-600 dark:text-surface-400">
            <div class="flex items-center justify-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <span>{{ $user->email }}</span>
            </div>
            @if($user->phone)
            <div class="flex items-center justify-center gap-2">
                <i data-lucide="phone" class="w-4 h-4"></i>
                <span>{{ $user->phone }}</span>
            </div>
            @endif
            @if($user->location)
            <div class="flex items-center justify-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                <span>{{ $user->location }}</span>
            </div>
            @endif
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
                class="relative w-full max-w-2xl bg-white dark:bg-surface-900 rounded-3xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-theme-100 dark:bg-theme-900/30 rounded-xl">
                            <i data-lucide="crop" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white" id="crop-modal-title">Crop Foto Profil</h3>
                            <p class="text-xs text-surface-500">Atur area foto yang ingin ditampilkan (rasio 1:1)</p>
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
                    <div class="relative max-h-[50vh] overflow-hidden rounded-2xl bg-black flex items-center justify-center">
                        <img 
                            id="profileCropperImage"
                            x-ref="cropperImage"
                            src=""
                            class="max-w-full max-h-[50vh] mx-auto block"
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
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
