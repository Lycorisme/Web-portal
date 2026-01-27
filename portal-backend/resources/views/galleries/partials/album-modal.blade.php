{{-- Album Modal (for viewing grouped images) --}}
<template x-teleport="body">
    <div 
        x-show="showAlbumModal"
        x-cloak
        class="fixed inset-0 z-[60]"
        @keydown.escape.window="closeAlbumModal()"
        @keydown.left.window="prevAlbumItem()"
        @keydown.right.window="nextAlbumItem()"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showAlbumModal"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/95 backdrop-blur-md"
        ></div>

        {{-- Close & Info Buttons --}}
        <div class="fixed top-4 right-4 z-30 flex items-center gap-2">
            {{-- Info Button --}}
            <button 
                @click="toggleInfoModal()"
                x-show="showAlbumModal"
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
                @click="closeAlbumModal()"
                x-show="showAlbumModal"
                x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="p-3 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all duration-300"
            >
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        {{-- Album Header Info - Smart Collection Style --}}
        <div 
            x-show="showAlbumModal && albumModalData"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed top-4 left-4 right-20 z-20"
        >
            <div class="flex items-center gap-4 p-3 rounded-2xl bg-black/40 backdrop-blur-xl border border-white/10 max-w-lg">
                {{-- Collection Icon --}}
                <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500 flex items-center justify-center shadow-lg shadow-violet-500/30 ring-2 ring-white/20">
                    <i data-lucide="layers" class="w-7 h-7 text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    {{-- Smart Collection Badge --}}
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-violet-500/20 text-violet-300 border border-violet-500/30">
                            <i data-lucide="sparkles" class="w-3 h-3"></i>
                            Smart Collection
                        </span>
                    </div>
                    {{-- Title --}}
                    <h2 class="text-lg font-bold text-white truncate" x-text="albumModalData?.title"></h2>
                    {{-- Meta Info --}}
                    <div class="flex items-center gap-3 text-sm text-white/60 mt-0.5">
                        <span x-show="albumModalData?.album" class="flex items-center gap-1">
                            <i data-lucide="hash" class="w-3.5 h-3.5 opacity-70"></i>
                            <span x-text="albumModalData?.album"></span>
                        </span>
                        <span class="flex items-center gap-1 text-violet-300">
                            <i data-lucide="image" class="w-3.5 h-3.5"></i>
                            <span x-text="albumModalData?.group_count + ' foto dalam koleksi'"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Controls --}}
        <div class="fixed inset-x-0 bottom-0 z-30 p-4 sm:p-0 sm:inset-0 sm:pointer-events-none flex flex-col sm:flex-row items-center justify-between pointer-events-none">
            {{-- Prev Button --}}
            <button 
                @click.stop="prevAlbumItem()"
                x-show="showAlbumModal && albumItems.length > 1"
                x-transition:enter="transition ease-out duration-300 delay-300"
                x-transition:enter-start="opacity-0 -translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="pointer-events-auto p-4 sm:p-3 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 backdrop-blur-sm rounded-full transition-all duration-300 hover:scale-110 active:scale-95 sm:fixed sm:left-4 sm:top-1/2 sm:-translate-y-1/2 absolute bottom-8 left-8 sm:static"
            >
                <i data-lucide="chevron-left" class="w-8 h-8 sm:w-10 sm:h-10"></i>
            </button>

            {{-- Next Button --}}
            <button 
                @click.stop="nextAlbumItem()"
                x-show="showAlbumModal && albumItems.length > 1"
                x-transition:enter="transition ease-out duration-300 delay-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="pointer-events-auto p-4 sm:p-3 text-white/70 hover:text-white bg-black/40 hover:bg-black/60 backdrop-blur-sm rounded-full transition-all duration-300 hover:scale-110 active:scale-95 sm:fixed sm:right-4 sm:top-1/2 sm:-translate-y-1/2 absolute bottom-8 right-8 sm:static"
            >
                <i data-lucide="chevron-right" class="w-8 h-8 sm:w-10 sm:h-10"></i>
            </button>
        </div>

        {{-- Main Content Area --}}
        <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-12 z-10 pointer-events-none pt-20 pb-40 sm:pb-32">
            {{-- Loading State --}}
            <div x-show="albumLoading" class="flex items-center justify-center">
                <div class="w-12 h-12 border-4 border-theme-500/30 border-t-theme-500 rounded-full animate-spin"></div>
            </div>

            {{-- Image Preview --}}
            <div 
                x-show="!albumLoading && currentAlbumItem"
                class="max-w-6xl w-full pointer-events-auto relative overflow-hidden"
            >
                <template x-if="currentAlbumItem">
                    <div 
                        :key="currentAlbumItem.id"
                        class="relative w-full"
                        x-bind="albumTransition"
                    >
                        <img 
                            :src="currentAlbumItem.image_url" 
                            :alt="currentAlbumItem.title"
                            class="max-w-full max-h-[60vh] mx-auto object-contain rounded-2xl shadow-2xl bg-black/50"
                            @click.stop
                        >
                        
                        {{-- Image Caption --}}
                        <div class="mt-4 text-center text-white">
                            <h3 class="text-lg font-semibold" x-text="currentAlbumItem.title"></h3>
                            <p class="text-sm text-white/50 mt-1" x-text="currentAlbumItem.created_at"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Thumbnail Strip (Bottom) - Smart Collection Style --}}
        <div 
            x-show="showAlbumModal && albumItems.length > 1 && !albumLoading"
            x-transition:enter="transition ease-out duration-500 delay-300"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed bottom-0 left-0 right-0 z-50 bg-gradient-to-t from-black/90 via-black/70 to-transparent backdrop-blur-xl border-t border-violet-500/20 pb-6 pt-8"
        >
            <div class="max-w-7xl mx-auto px-4">
                {{-- Thumbnails --}}
                <div class="flex items-center justify-center gap-3 overflow-x-auto scrollbar-hide py-2 px-4 mask-linear mb-3">
                    <template x-for="(item, index) in albumItems" :key="item.id">
                        <button 
                            @click="goToAlbumItem(index)"
                            class="relative flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden transition-all duration-300 group"
                            :class="albumCurrentIndex === index 
                                ? 'ring-2 ring-violet-500 ring-offset-2 ring-offset-black scale-110 z-10 opacity-100 shadow-lg shadow-violet-500/40' 
                                : 'opacity-50 hover:opacity-100 hover:scale-105 grayscale hover:grayscale-0'"
                        >
                            <img 
                                :src="item.thumbnail_url || item.image_url" 
                                :alt="item.title"
                                class="w-full h-full object-cover"
                            >
                            {{-- Active Indicator --}}
                            <div 
                                x-show="albumCurrentIndex === index"
                                class="absolute inset-0 bg-gradient-to-t from-violet-500/30 to-transparent"
                            ></div>
                            {{-- Index Number --}}
                            <div 
                                class="absolute bottom-1 right-1 w-5 h-5 rounded-md text-[10px] font-bold flex items-center justify-center"
                                :class="albumCurrentIndex === index 
                                    ? 'bg-violet-500 text-white' 
                                    : 'bg-black/60 text-white/70'"
                                x-text="index + 1"
                            ></div>
                        </button>
                    </template>
                </div>
                
                {{-- Counter & Navigation Hint --}}
                <div class="flex items-center justify-center gap-4 text-xs font-medium text-white/50">
                    <span class="hidden sm:flex items-center gap-1.5 text-violet-300/60">
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-[10px]">←</kbd> Prev
                    </span>
                    <span class="px-4 py-1.5 bg-violet-500/20 rounded-full text-violet-300 border border-violet-500/30">
                        <span x-text="albumCurrentIndex + 1" class="font-bold"></span> 
                        <span class="text-violet-300/60">dari</span> 
                        <span x-text="albumItems.length" class="font-bold"></span>
                    </span>
                    <span class="hidden sm:flex items-center gap-1.5 text-violet-300/60">
                        Next <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-[10px]">→</kbd>
                    </span>
                </div>
            </div>
        </div>
        {{-- Info Modal (Reused with Data Mapping) --}}
        <div x-data="{ 
            get previewItem() { return this.currentAlbumItem; },
            get previewCurrentIndex() { return this.albumCurrentIndex; },
            get galleries() { return this.albumItems; }
        }">
            @include('galleries.partials.info-modal')
        </div>
    </div>
</template>
