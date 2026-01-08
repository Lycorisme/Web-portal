{{-- Image/Video Preview Modal (Lightbox) --}}
<template x-teleport="body">
    <div 
        x-show="showPreviewModal"
        x-cloak
        class="fixed inset-0 z-[60]"
        @keydown.escape.window="closePreview()"
        @keydown.left.window="prevPreview()"
        @keydown.right.window="nextPreview()"
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
            @click="closePreview()"
        ></div>

        {{-- Close Button --}}
        <button 
            @click="closePreview()"
            x-show="showPreviewModal"
            x-transition:enter="transition ease-out duration-300 delay-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed top-4 right-4 p-3 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all duration-300 z-20"
        >
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>

        {{-- Navigation Buttons --}}
        <button 
            @click.stop="prevPreview()"
            x-show="showPreviewModal && galleries.length > 1"
            x-transition:enter="transition ease-out duration-300 delay-300"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="fixed left-4 top-1/2 -translate-y-1/2 p-3 text-white/70 hover:text-white bg-black/30 hover:bg-black/50 rounded-full transition-all duration-300 z-20 hover:scale-110"
        >
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </button>

        <button 
            @click.stop="nextPreview()"
            x-show="showPreviewModal && galleries.length > 1"
            x-transition:enter="transition ease-out duration-300 delay-300"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="fixed right-4 top-1/2 -translate-y-1/2 p-3 text-white/70 hover:text-white bg-black/30 hover:bg-black/50 rounded-full transition-all duration-300 z-20 hover:scale-110"
        >
            <i data-lucide="chevron-right" class="w-6 h-6"></i>
        </button>

        {{-- Content Container --}}
        <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-8 z-10 pointer-events-none">
            <div 
                x-show="showPreviewModal && previewItem"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="max-w-6xl w-full pointer-events-auto"
            >
                <template x-if="previewItem">
                    <div class="relative">
                        {{-- Image Preview --}}
                        <template x-if="previewItem.media_type === 'image'">
                            <div class="relative">
                                <img 
                                    :src="previewItem.image_url" 
                                    :alt="previewItem.title"
                                    class="max-w-full max-h-[75vh] mx-auto object-contain rounded-2xl shadow-2xl"
                                    @click.stop
                                >
                            </div>
                        </template>

                        {{-- Video Preview with YouTube Embed --}}
                        <template x-if="previewItem.media_type === 'video'">
                            <div class="relative w-full max-w-4xl mx-auto" @click.stop>
                                <template x-if="getYoutubeId(previewItem.video_url)">
                                    <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl bg-black">
                                        <iframe 
                                            :src="'https://www.youtube.com/embed/' + getYoutubeId(previewItem.video_url) + '?autoplay=1&rel=0'"
                                            class="w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen
                                        ></iframe>
                                    </div>
                                </template>
                                <template x-if="!getYoutubeId(previewItem.video_url)">
                                    <div class="aspect-video flex items-center justify-center bg-surface-800 rounded-2xl">
                                        <a :href="previewItem.video_url" target="_blank" class="flex flex-col items-center text-white hover:text-theme-400 transition-colors">
                                            <i data-lucide="external-link" class="w-12 h-12 mb-3"></i>
                                            <p class="text-sm font-medium">Buka Video di Tab Baru</p>
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Caption --}}
                        <div 
                            class="mt-6 text-center text-white"
                            x-transition:enter="transition ease-out duration-500 delay-200"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                        >
                            <h3 class="text-xl font-bold mb-1" x-text="previewItem.title"></h3>
                            <p class="text-sm text-white/60" x-text="previewItem.album || ''"></p>
                            <template x-if="previewItem.description">
                                <p class="text-sm text-white/50 mt-2 max-w-2xl mx-auto" x-text="previewItem.description"></p>
                            </template>
                            <p class="text-xs text-white/40 mt-4">
                                <span x-text="(previewCurrentIndex + 1)"></span> / <span x-text="galleries.length"></span>
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
