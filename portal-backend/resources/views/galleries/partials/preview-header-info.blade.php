<div 
    x-show="showPreviewModal && previewItem"
    x-transition:enter="transition ease-out duration-300 delay-100"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="fixed top-6 left-6 z-[70] max-w-[60%] sm:max-w-2xl flex flex-col gap-0.5 pointer-events-none leading-tight"
>
    {{-- Counter & Category Badge --}}
    <div class="flex items-center gap-3 mb-1">
        {{-- Media Type --}}
        <span 
            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wider uppercase border"
            :class="previewItem?.media_type === 'video' 
                ? 'bg-red-500/10 text-red-400 border-red-500/20' 
                : 'bg-white/10 text-white/80 border-white/10'"
        >
            <i :data-lucide="previewItem?.media_type === 'video' ? 'video' : 'image'" class="w-3 h-3"></i>
            <span x-text="previewItem?.media_type === 'video' ? 'VIDEO' : 'IMG'"></span>
        </span>
    </div>

    {{-- Title --}}
    <h3 
        class="text-white font-bold text-lg sm:text-2xl drop-shadow-md line-clamp-1"
        x-text="previewItem?.title || 'Untitled'"
    ></h3>
    
    {{-- Album / Context --}}
    <div class="flex items-center gap-2 text-white/60 text-xs sm:text-sm font-medium">
        <template x-if="previewItem?.album">
            <div class="flex items-center gap-1.5">
                <i data-lucide="layers" class="w-3.5 h-3.5 opacity-70"></i>
                <span x-text="previewItem.album" class="truncate max-w-[200px]"></span>
            </div>
        </template>
        
        {{-- Date if available (Optional) --}}
        <template x-if="previewItem?.date">
            <div class="flex items-center gap-1.5">
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span x-text="previewItem.date"></span>
            </div>
        </template>
    </div>
</div>
