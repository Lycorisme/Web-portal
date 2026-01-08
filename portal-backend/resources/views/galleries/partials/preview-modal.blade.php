{{-- Image/Video Preview Modal --}}
<template x-teleport="body">
    <div 
        x-show="showPreviewModal"
        x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90"
        @click="closePreview()"
        @keydown.escape.window="closePreview()"
    >
        {{-- Close Button --}}
        <button 
            @click="closePreview()"
            class="absolute top-4 right-4 p-2 text-white/70 hover:text-white transition-colors z-10"
        >
            <i data-lucide="x" class="w-8 h-8"></i>
        </button>

        {{-- Navigation Buttons --}}
        <button 
            @click.stop="prevPreview()"
            x-show="galleries.length > 1"
            class="absolute left-4 p-3 text-white/70 hover:text-white bg-black/30 hover:bg-black/50 rounded-full transition-all z-10"
        >
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </button>

        <button 
            @click.stop="nextPreview()"
            x-show="galleries.length > 1"
            class="absolute right-4 p-3 text-white/70 hover:text-white bg-black/30 hover:bg-black/50 rounded-full transition-all z-10"
        >
            <i data-lucide="chevron-right" class="w-6 h-6"></i>
        </button>

        {{-- Content --}}
        <div 
            @click.stop
            class="max-w-6xl w-full max-h-[90vh]"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <template x-if="previewItem">
                <div class="relative">
                    {{-- Image Preview --}}
                    <template x-if="previewItem.media_type === 'image'">
                        <img 
                            :src="previewItem.image_url" 
                            :alt="previewItem.title"
                            class="max-w-full max-h-[80vh] mx-auto object-contain rounded-lg shadow-2xl"
                        >
                    </template>

                    {{-- Video Preview --}}
                    <template x-if="previewItem.media_type === 'video'">
                        <div class="relative aspect-video max-h-[80vh] mx-auto">
                            <template x-if="getYoutubeId(previewItem.video_url)">
                                <iframe 
                                    :src="'https://www.youtube.com/embed/' + getYoutubeId(previewItem.video_url) + '?autoplay=1'"
                                    class="w-full h-full rounded-lg"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </template>
                            <template x-if="!getYoutubeId(previewItem.video_url)">
                                <div class="flex items-center justify-center h-full bg-surface-800 rounded-lg">
                                    <a :href="previewItem.video_url" target="_blank" class="text-white hover:text-theme-400 transition-colors">
                                        <i data-lucide="external-link" class="w-12 h-12"></i>
                                        <p class="mt-2 text-sm">Buka Video</p>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Caption --}}
                    <div class="mt-4 text-center text-white">
                        <h3 class="text-lg font-semibold" x-text="previewItem.title"></h3>
                        <p class="text-sm text-white/60 mt-1" x-text="previewItem.album || ''"></p>
                        <p class="text-xs text-white/40 mt-2" x-text="(previewCurrentIndex + 1) + ' / ' + galleries.length"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
