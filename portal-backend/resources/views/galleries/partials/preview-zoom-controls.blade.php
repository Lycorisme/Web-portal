<div 
    x-show="showZoomControls && previewItem && previewItem.media_type === 'image'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-8"
    class="fixed bottom-24 sm:bottom-12 left-1/2 -translate-x-1/2 z-50 flex items-center justify-between pointer-events-auto h-12 px-1 min-w-[200px] w-[280px]"
    @click.stop
>
    <div class="absolute inset-0 bg-surface-900/80 backdrop-blur-xl border border-white/10 rounded-full shadow-2xl"></div>

    <div class="relative z-10 w-full flex items-center justify-between px-2">
        {{-- Zoom Out --}}
        <button 
            @click="zoomOut()"
            class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-all active:scale-95"
            :class="{'opacity-40 cursor-not-allowed': zoomScale <= 1}"
            :disabled="zoomScale <= 1"
        >
            <i data-lucide="minus" class="w-4 h-4"></i>
        </button>

        {{-- Slider / Indicator --}}
        <div class="flex-1 px-4 flex items-center justify-center relative h-full">
            {{-- Track --}}
            <div class="w-full h-1 bg-white/20 rounded-full relative overflow-hidden">
                <div 
                    class="absolute top-0 left-0 h-full bg-theme-500 rounded-full transition-all duration-300"
                    :style="`width: ${(zoomScale - 1) * 50}%`"
                ></div>
            </div>
            
            {{-- Knob (Visual Only) --}}
            <div 
                class="absolute w-4 h-4 bg-white border-2 border-theme-500 rounded-full shadow-lg transform -translate-x-1/2 transition-all duration-300 pointer-events-none"
                 :style="`left: calc(16px + (100% - 32px) * ${(zoomScale - 1) / 2})`"
            ></div>
        </div>

        {{-- Zoom In --}}
        <button 
            @click="zoomIn()"
            class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-all active:scale-95"
            :class="{'opacity-40 cursor-not-allowed': zoomScale >= 3}"
            :disabled="zoomScale >= 3"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
        </button>
        
        {{-- Reset (Optional: Hover to show?) --}}
        <!-- Keeping it simple as per design -->
    </div>
</div>
