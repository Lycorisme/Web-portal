{{-- Image/Video Preview Modal (Lightbox) --}}
<template x-teleport="body">
    <div 
        x-show="showPreviewModal"
        x-cloak
        class="fixed inset-0 z-[60]"
        @keydown.left.window="prevPreview()"
        @keydown.right.window="nextPreview()"
        @keydown.escape.window="showInfoModal ? showInfoModal = false : closePreview()"
        @touchstart="onTouchStart($event)"
        @touchend="onTouchEnd($event)"
        @wheel.prevent="handleMouseWheel($event)"
    >
        {{-- Backdrop with smooth transition --}}
        <div 
            x-show="showPreviewModal"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/95 backdrop-blur-md"
        ></div>

        {{-- Top Left Info (Modern & Clean) - Includes integrated gradient --}}
        @include('galleries.partials.preview-header-info')


        {{-- Top Controls --}}
        <div class="fixed top-4 right-4 z-[80] flex items-center gap-2">
            {{-- Zoom Toggle Button (For Images and Videos) --}}
            <button 
                @click="toggleZoomControls()"
                x-show="showPreviewModal"
                x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="p-3 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all duration-300"
                :class="{ 'bg-white/20 text-white': showZoomControls }"
            >
                <i data-lucide="zoom-in" class="w-6 h-6"></i>
            </button>
            {{-- Info Button --}}

            <button 
                @click="toggleInfoModal()"
                x-show="showPreviewModal"
                x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="p-3 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all duration-300"
                :class="{ 'bg-white/20 text-white': showInfoModal }"
            >
                <i data-lucide="info" class="w-6 h-6"></i>
            </button>
            {{-- Close Button --}}
            <button 
                @click="closePreview()"
                x-show="showPreviewModal"
                x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="p-3 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all duration-300"
            >
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        {{-- Navigation Controls Container --}}
        <div class="fixed inset-x-0 bottom-0 z-[75] p-4 sm:p-0 sm:inset-0 sm:pointer-events-none flex flex-col sm:flex-row items-center justify-between pointer-events-none">
            
            {{-- Prev Button (Desktop: Left Center, Mobile: Bottom Left) --}}
            <button 
                @click.stop="prevPreview()"
                x-show="showPreviewModal && galleries.length > 1"
                x-transition:enter="transition ease-out duration-300 delay-300"
                x-transition:enter-start="opacity-0 -translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="pointer-events-auto p-4 sm:p-3 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 backdrop-blur-sm rounded-full transition-all duration-300 hover:scale-110 active:scale-95 sm:fixed sm:left-4 sm:top-1/2 sm:-translate-y-1/2 absolute bottom-8 left-8 sm:static"
            >
                <i data-lucide="chevron-left" class="w-8 h-8 sm:w-10 sm:h-10"></i>
            </button>

            {{-- Next Button (Desktop: Right Center, Mobile: Bottom Right) --}}
            <button 
                @click.stop="nextPreview()"
                x-show="showPreviewModal && galleries.length > 1"
                x-transition:enter="transition ease-out duration-300 delay-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="pointer-events-auto p-4 sm:p-3 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 backdrop-blur-sm rounded-full transition-all duration-300 hover:scale-110 active:scale-95 sm:fixed sm:right-4 sm:top-1/2 sm:-translate-y-1/2 absolute bottom-8 right-8 sm:static"
            >
                <i data-lucide="chevron-right" class="w-8 h-8 sm:w-10 sm:h-10"></i>
            </button>
            
            {{-- Desktop Pagination Indicator (Centered Bottom) --}}
            <div 
                class="pointer-events-auto fixed bottom-8 left-1/2 -translate-x-1/2 px-4 py-2 bg-black/40 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium"
                x-show="showPreviewModal && previewList.length > 1"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                <span x-text="(previewCurrentIndex + 1)"></span> / <span x-text="previewList.length"></span>
            </div>
        </div>

        {{-- Content Container - z-index higher to allow full zoom --}}
        <div class="fixed inset-0 flex items-center justify-center z-[65] pointer-events-none px-4 sm:px-16">
            <div 
                x-show="showPreviewModal && previewItem"
                class="w-full h-full pointer-events-auto relative flex items-center justify-center"
                :class="{ 'cursor-default': true }"
            >
                <template x-if="previewItem">
                    <div 
                        x-key="previewItem.id"
                        class="relative flex items-center justify-center"
                        :style="`transform: scale(${zoomScale}) translate(${panX}px, ${panY}px); transition: transform ${isSliderDragging ? '0ms' : '300ms'} cubic-bezier(0.4, 0, 0.2, 1);`"
                        x-bind="previewTransition"
                    >
                        {{-- Image Preview with Lazy Loading --}}
                        <template x-if="previewItem.media_type === 'image'">
                            <div class="relative flex items-center justify-center overflow-visible">
                                <img 
                                    :src="getImageWithCache(previewItem.image_url)" 
                                    :alt="previewItem.title"
                                    class="max-w-[90vw] max-h-[85vh] object-contain rounded-xl sm:rounded-2xl shadow-2xl bg-black/50"
                                    :class="{ 'cursor-grabbing': zoomScale > 1 && isPanning, 'cursor-default': !isPanning || zoomScale <= 1 }"
                                    @mousedown.prevent="startPan"
                                    @mousemove.window="handlePan"
                                    @mouseup.window="endPan"
                                    @click.stop
                                    loading="lazy"
                                >
                            </div>
                        </template>

                        {{-- Video Preview with YouTube Embed - No autoplay until play button --}}
                        <template x-if="previewItem.media_type === 'video'">
                            <div class="relative flex items-center justify-center overflow-visible" @click.stop>
                                <div class="w-[90vw] max-w-4xl aspect-video rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl bg-black">
                                    <template x-if="getYoutubeId(previewItem.video_url)">
                                        <div class="w-full h-full relative">
                                            {{-- YouTube Thumbnail (before play) --}}
                                            <template x-if="!videoShouldAutoplay">
                                                <div 
                                                    class="absolute inset-0 flex items-center justify-center bg-black cursor-pointer group"
                                                    @click="videoShouldAutoplay = true"
                                                >
                                                    <img 
                                                        :src="'https://img.youtube.com/vi/' + getYoutubeId(previewItem.video_url) + '/maxresdefault.jpg'"
                                                        class="w-full h-full object-cover"
                                                        :alt="previewItem.title"
                                                    >
                                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                                                    <button class="absolute w-20 h-20 rounded-full bg-red-600 flex items-center justify-center shadow-lg transform transition-all duration-300 group-hover:scale-110">
                                                        <i data-lucide="play" class="w-8 h-8 text-white ml-1"></i>
                                                    </button>
                                                </div>
                                            </template>
                                            {{-- YouTube iframe (after play clicked) --}}
                                            <template x-if="videoShouldAutoplay">
                                                <iframe 
                                                    :src="'https://www.youtube.com/embed/' + getYoutubeId(previewItem.video_url) + '?autoplay=1&rel=0'"
                                                    class="w-full h-full"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    allowfullscreen
                                                ></iframe>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!getYoutubeId(previewItem.video_url)">
                                        <div class="w-full h-full flex items-center justify-center bg-surface-800">
                                            <a :href="previewItem.video_url" target="_blank" class="flex flex-col items-center text-white hover:text-theme-400 transition-colors p-6 text-center">
                                                <i data-lucide="external-link" class="w-12 h-12 mb-3"></i>
                                                <p class="text-sm font-medium">Buka Video di Tab Baru</p>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Zoom Controls - z-index highest --}}
        @include('galleries.partials.preview-zoom-controls')

        {{-- Info Modal (Separate File) --}}
        @include('galleries.partials.info-modal')
    </div>
</template>
