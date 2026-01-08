{{-- Gallery Info Sidebar (Modern Floating Panel) --}}
<div 
    x-show="showInfoModal && previewItem"
    x-transition:enter="transition ease-[cubic-bezier(0.19,1,0.22,1)] duration-500"
    x-transition:enter-start="opacity-0 translate-x-20"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-[cubic-bezier(0.19,1,0.22,1)] duration-400"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-20"
    class="fixed inset-y-0 right-0 z-[100] w-full sm:w-[400px] h-full sm:p-4 pointer-events-none flex flex-col justify-end sm:justify-center"
>
    <!-- 
      Backdrop for mobile only 
      This ensures looking at info on mobile feels focused, 
      but on desktop it feels like a side panel 
    -->
    <div 
        class="absolute inset-0 bg-black/60 sm:hidden pointer-events-auto"
        @click="toggleInfoModal()"
    ></div>

    {{-- Panel Container --}}
    <div 
        class="relative w-full h-[85vh] sm:h-full max-h-full flex flex-col pointer-events-auto bg-surface-900/95 sm:bg-black/80 backdrop-blur-2xl sm:rounded-3xl border-t sm:border border-white/10 shadow-2xl overflow-hidden"
        @click.stop
    >
        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-theme-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-60 h-60 bg-blue-500/10 rounded-full blur-[80px] pointer-events-none"></div>

        {{-- Header --}}
        <div class="relative z-10 flex items-center justify-between px-6 py-5 border-b border-white/5 bg-white/[0.02]">
            <div>
                <h3 class="text-lg font-bold text-white tracking-tight">Informasi</h3>
                <p class="text-xs text-white/40 font-medium">Detail Media</p>
            </div>
            
            {{-- Close Button --}}
            <button 
                @click="toggleInfoModal()"
                class="p-2 -mr-2 text-white/50 hover:text-white hover:bg-white/10 rounded-full transition-all"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar relative z-10 p-6 space-y-6">
            
            {{-- 1. Main Meta (Date & Title) --}}
            <div>
                <div class="flex items-baseline justify-between mb-1">
                    <h4 class="text-2xl font-bold text-white leading-tight" x-text="previewItem.title || 'Untitled'"></h4>
                    {{-- Type Badge --}}
                    <span 
                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider ml-3"
                        :class="previewItem.media_type === 'image' 
                            ? 'bg-blue-500/20 text-blue-300 border border-blue-500/20' 
                            : 'bg-rose-500/20 text-rose-300 border border-rose-500/20'"
                        x-text="previewItem.media_type === 'image' ? 'IMG' : 'VID'"
                    ></span>
                </div>
                <p class="text-white/50 text-sm font-medium flex items-center gap-2">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span x-text="formatDateIndo(previewItem.created_at)"></span>
                </p>
            </div>

            {{-- 2. Uploader Card (Mini) --}}
            <template x-if="previewItem.uploader">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center ring-2 ring-white/10">
                        <span class="text-xs font-bold text-white" x-text="previewItem.uploader.name.substring(0,2).toUpperCase()"></span>
                    </div>
                    <div>
                        <p class="text-xs text-white/40 uppercase tracking-wide font-semibold">Diunggah Oleh</p>
                        <p class="text-sm font-medium text-white" x-text="previewItem.uploader.name"></p>
                    </div>
                </div>
            </template>

            {{-- 3. Description --}}
            <template x-if="previewItem.description">
                <div>
                    <h5 class="text-xs text-white/40 uppercase tracking-wider font-bold mb-3 flex items-center gap-2">
                        <i data-lucide="align-left" class="w-3 h-3"></i>
                        Deskripsi
                    </h5>
                    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-sm leading-relaxed text-white/80">
                        <p x-text="previewItem.description"></p>
                    </div>
                </div>
            </template>

            {{-- 4. Details Grid --}}
            <div>
                <h5 class="text-xs text-white/40 uppercase tracking-wider font-bold mb-3 flex items-center gap-2">
                    <i data-lucide="list" class="w-3 h-3"></i>
                    Detail Properti
                </h5>
                <div class="grid grid-cols-1 gap-2.5">
                    {{-- Album --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-purple-500/10 text-purple-400">
                                <i data-lucide="folder" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm text-white/60">Album</span>
                        </div>
                        <span class="text-sm font-medium text-white truncate max-w-[150px]" x-text="previewItem.album || '-'"></span>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm text-white/60">Lokasi</span>
                        </div>
                        <span class="text-sm font-medium text-white truncate max-w-[150px]" x-text="previewItem.location || '-'"></span>
                    </div>

                    {{-- Event Date --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-amber-500/10 text-amber-400">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm text-white/60">Tgl. Event</span>
                        </div>
                        <span class="text-sm font-medium text-white" x-text="previewItem.event_date || '-'"></span>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-teal-500/10 text-teal-400">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm text-white/60">Status</span>
                        </div>
                        <span 
                            class="text-sm font-medium px-2 py-0.5 rounded-md"
                            :class="previewItem.is_published 
                                ? 'bg-emerald-500/10 text-emerald-400' 
                                : 'bg-amber-500/10 text-amber-400'"
                            x-text="previewItem.is_published ? 'Published' : 'Draft'"
                        ></span>
                    </div>
                </div>
            </div>

            {{-- 5. Camera Info Placeholder --}}
            <div>
                <h5 class="text-xs text-white/40 uppercase tracking-wider font-bold mb-3 flex items-center gap-2">
                    <i data-lucide="aperture" class="w-3 h-3"></i>
                    Info Kamera
                </h5>
                <div class="p-4 rounded-2xl bg-gradient-to-br from-white/5 to-white/[0.02] border border-white/5 flex items-center gap-4 opacity-60">
                    <i data-lucide="camera-off" class="w-8 h-8 text-white/20"></i>
                    <div>
                        <p class="text-sm font-medium text-white/50">Data EXIF tidak tersedia</p>
                        <p class="text-xs text-white/30">Kamera atau lensa tidak terdeteksi</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="p-4 border-t border-white/5 bg-white/[0.02]">
            <a 
                :href="previewItem.image_url" 
                download 
                class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium transition-all group"
            >
                <i data-lucide="download" class="w-4 h-4 text-white/70 group-hover:text-white transition-colors"></i>
                Download Original
            </a>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 100px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
