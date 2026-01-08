{{-- Zoom Controls - Telegram-style draggable slider --}}
<div 
    x-show="showZoomControls"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-8"
    class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[90] flex items-center justify-between pointer-events-auto h-14 px-2 min-w-[260px] w-[320px]"
    @click.stop
>
    <div class="absolute inset-0 bg-surface-900/90 backdrop-blur-xl border border-white/10 rounded-full shadow-2xl"></div>

    <div class="relative z-10 w-full flex items-center justify-between px-3 gap-3">
        {{-- Current Zoom Percentage Display --}}
        <div class="flex-shrink-0 w-14 text-center">
            <span class="text-sm font-semibold text-white" x-text="zoomPercent + '%'"></span>
        </div>

        {{-- Zoom Out --}}
        <button 
            @click="zoomOut()"
            class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-all active:scale-95"
            :class="{'opacity-40 cursor-not-allowed': zoomPercent <= 100}"
            :disabled="zoomPercent <= 100"
        >
            <i data-lucide="minus" class="w-5 h-5"></i>
        </button>

        {{-- Draggable Slider Container --}}
        <div 
            class="flex-1 h-10 flex items-center relative cursor-pointer select-none"
            @mousedown.prevent="startSliderDrag($event)"
            @touchstart.prevent="startSliderDragTouch($event)"
            x-ref="sliderTrack"
        >
            {{-- Track Background --}}
            <div class="w-full h-1.5 bg-white/20 rounded-full relative overflow-hidden">
                {{-- Progress Fill --}}
                <div 
                    class="absolute top-0 left-0 h-full bg-gradient-to-r from-theme-400 to-theme-500 rounded-full"
                    :style="`width: ${((zoomPercent - 100) / 200) * 100}%; transition: width ${isSliderDragging ? '0ms' : '300ms'} ease-out;`"
                ></div>
            </div>
            
            {{-- Draggable Knob --}}
            <div 
                class="absolute w-5 h-5 bg-white rounded-full shadow-lg shadow-black/30 transform -translate-x-1/2 border-2 border-theme-500"
                :class="{ 'scale-125': isSliderDragging }"
                :style="`left: ${((zoomPercent - 100) / 200) * 100}%; transition: ${isSliderDragging ? 'transform 100ms' : 'left 300ms ease-out, transform 100ms'};`"
            ></div>
        </div>

        {{-- Zoom In --}}
        <button 
            @click="zoomIn()"
            class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-all active:scale-95"
            :class="{'opacity-40 cursor-not-allowed': zoomPercent >= 300}"
            :disabled="zoomPercent >= 300"
        >
            <i data-lucide="plus" class="w-5 h-5"></i>
        </button>

        {{-- Reset Button --}}
        <button 
            @click="resetZoom()"
            x-show="zoomPercent !== 100"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-all active:scale-95"
            title="Reset zoom"
        >
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
        </button>
    </div>
</div>
