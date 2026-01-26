{{-- Preview Header Info - Auto-hide functionality --}}
<div 
    x-data="{ 
        showHeaderInfo: false,
        headerTimeout: null,
        showAndResetTimer() {
            // Show header info and reset timer for 3 seconds
            this.showHeaderInfo = true;
            clearTimeout(this.headerTimeout);
            this.headerTimeout = setTimeout(() => { this.showHeaderInfo = false; }, 3000);
        },
        initHeaderTracking() {
            // Show header info initially for 3 seconds then hide
            this.showAndResetTimer();
        }
    }"
    x-init="
        $watch('showPreviewModal', value => { if(value) initHeaderTracking(); });
        $watch('previewCurrentIndex', () => { if(showPreviewModal) showAndResetTimer(); });
    "
    @mousemove.window="
        if(showPreviewModal && $event.clientY < 120) { 
            showAndResetTimer();
        }
    "
    class="fixed top-0 left-0 right-0 z-[70] pointer-events-none"
>
    {{-- Gradient overlay that appears with header --}}
    <div 
        x-show="showPreviewModal && previewItem && showHeaderInfo"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/80 via-black/40 to-transparent"
    ></div>

    {{-- Header Info Content --}}
    <div 
        x-show="showPreviewModal && previewItem && showHeaderInfo"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="relative pt-6 pl-6 max-w-[60%] sm:max-w-2xl flex flex-col gap-0.5 leading-tight"
    >
        {{-- Counter & Category Badge --}}
        <div class="flex items-center gap-3 mb-1">
            {{-- Media Type --}}
            <span 
                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wider uppercase border backdrop-blur-sm"
                :class="previewItem?.media_type === 'video' 
                    ? 'bg-red-500/20 text-red-400 border-red-500/30' 
                    : 'bg-white/10 text-white/80 border-white/20'"
            >
                <i :data-lucide="previewItem?.media_type === 'video' ? 'video' : 'image'" class="w-3 h-3"></i>
                <span x-text="previewItem?.media_type === 'video' ? 'VIDEO' : 'IMG'"></span>
            </span>
        </div>

        {{-- Title --}}
        <h3 
            class="text-white font-bold text-lg sm:text-2xl drop-shadow-lg line-clamp-1"
            x-text="previewItem?.title || 'Untitled'"
        ></h3>
        
        {{-- Album / Context --}}
        <div class="flex items-center gap-2 text-white/70 text-xs sm:text-sm font-medium">
            <template x-if="previewItem?.album">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="layers" class="w-3.5 h-3.5 opacity-70"></i>
                    <span x-text="previewItem.album" class="truncate max-w-[200px]"></span>
                </div>
            </template>
            
            {{-- Date if available (Optional) --}}
            <template x-if="previewItem?.date">
                <div class="flex items-center gap-1.5">
                    <span class="w-1 h-1 rounded-full bg-white/40"></span>
                    <span x-text="previewItem.date"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- Hint indicator when header is hidden --}}
    <div 
        x-show="showPreviewModal && previewItem && !showHeaderInfo"
        x-transition:enter="transition ease-out duration-500 delay-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute top-3 left-1/2 -translate-x-1/2 flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/40 backdrop-blur-sm text-white/50 text-xs pointer-events-none"
    >
        <i data-lucide="chevron-up" class="w-3 h-3 animate-bounce"></i>
        <span>Hover untuk info</span>
    </div>
</div>
