{{-- Thumbnail Strip (Bottom Navigation) --}}
<div 
    x-show="!showInfoModal && previewList.length > 1"
    x-transition:enter="transition ease-out duration-500 delay-300"
    x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="fixed bottom-0 left-0 right-0 z-50 bg-black/60 backdrop-blur-xl border-t border-white/10 pb-6 pt-6"
>
    <div class="max-w-7xl mx-auto px-4">
        {{-- Thumbnails Carousel --}}
        <div class="flex items-center justify-center gap-3 overflow-x-auto scrollbar-hide py-2 px-4 mask-linear mb-2">
            <template x-for="(item, index) in previewList" :key="item.id || index">
                <button 
                    @click="goToPreview(index)"
                    class="relative flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden transition-all duration-300 group"
                    :class="previewCurrentIndex === index 
                        ? 'ring-2 ring-theme-500 ring-offset-2 ring-offset-black/50 scale-110 z-10 opacity-100 shadow-lg shadow-theme-500/20' 
                        : 'opacity-40 hover:opacity-100 hover:scale-105 grayscale hover:grayscale-0'"
                >
                    {{-- Video Indicator --}}
                    <template x-if="item.media_type === 'video'">
                        <div class="absolute inset-0 flex items-center justify-center z-10">
                            <div class="w-8 h-8 rounded-full bg-black/60 flex items-center justify-center">
                                <i data-lucide="play" class="w-4 h-4 text-white"></i>
                            </div>
                        </div>
                    </template>

                    {{-- Album/Group Indicator --}}
                    <template x-if="item.is_album_group">
                        <div class="absolute top-1 right-1 z-10 px-1.5 py-0.5 bg-black/60 backdrop-blur-sm rounded text-[10px] text-white font-medium">
                            <i data-lucide="layers" class="w-3 h-3 inline-block"></i>
                        </div>
                    </template>

                    {{-- Thumbnail Image --}}
                    <img 
                        :src="item.thumbnail_url || item.image_url" 
                        :alt="item.title"
                        class="w-full h-full object-cover"
                    >

                    {{-- Active Indicator Overlay --}}
                    <div 
                        x-show="previewCurrentIndex === index"
                        class="absolute inset-0 bg-theme-500/10"
                    ></div>
                </button>
            </template>
        </div>
        
        {{-- Counter & Navigation Hint --}}
        <div class="flex items-center justify-center gap-4 text-xs font-medium text-white/50">
            <span class="hidden sm:flex items-center gap-1 hover:text-white/70 transition-colors cursor-pointer" @click="prevPreview()">
                <i data-lucide="arrow-left" class="w-3 h-3"></i> Prev
            </span>
            <span class="px-3 py-1 bg-white/10 rounded-full text-white/90">
                <span x-text="previewCurrentIndex + 1"></span> / <span x-text="previewList.length"></span>
            </span>
            <span class="hidden sm:flex items-center gap-1 hover:text-white/70 transition-colors cursor-pointer" @click="nextPreview()">
                Next <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </span>
        </div>
    </div>
</div>
