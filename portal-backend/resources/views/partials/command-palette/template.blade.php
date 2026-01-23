{{-- Command Palette / Global Search - Template --}}
{{-- Part 1: HTML Template (~200 lines) --}}

<div x-data="commandPalette()"
     x-on:keydown.escape.window="close()"
     x-on:keydown.ctrl.k.window.prevent="toggle()"
     x-on:keydown.meta.k.window.prevent="toggle()"
     x-on:keydown.arrow-down.prevent="navigateDown()"
     x-on:keydown.arrow-up.prevent="navigateUp()"
     x-on:keydown.enter.prevent="selectCurrent()"
     x-on:keydown.tab.prevent="nextMode()">

    {{-- Command Palette Modal --}}
    <div x-show="isOpen"
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-cloak>

    {{-- Backdrop --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-surface-950/60 dark:bg-black/70 backdrop-blur-sm"></div>

    {{-- Modal Container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.outside="close()"
             class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white/95 dark:bg-surface-900/95 backdrop-blur-xl shadow-2xl shadow-surface-900/40 dark:shadow-black/60 ring-1 ring-white/20 dark:ring-surface-700/50">

            {{-- Top Gradient Accent --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-theme-400 via-theme-500 to-theme-600"></div>
            
            {{-- Decorative Glow --}}
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-theme-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-theme-600/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- Search Header --}}
            <div class="relative flex items-center gap-4 px-6 py-5 border-b border-surface-200/50 dark:border-surface-700/50">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-theme-gradient flex items-center justify-center shadow-lg shadow-theme-500/25">
                    <i data-lucide="search" class="w-5 h-5 text-white"></i>
                </div>
                <input
                    x-ref="searchInput"
                    x-model="query"
                    @input.debounce.300ms="handleInput()"
                    type="text"
                    :placeholder="getPlaceholder()"
                    class="flex-1 bg-transparent text-lg font-medium text-surface-900 dark:text-white placeholder:text-surface-400 dark:placeholder:text-surface-500 outline-none">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-theme-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <kbd class="hidden sm:inline-flex items-center px-2.5 py-1.5 text-xs font-semibold text-surface-500 bg-surface-100/80 dark:bg-surface-800/80 rounded-lg border border-surface-300/50 dark:border-surface-600/50 shadow-sm">
                        ESC
                    </kbd>
                </div>
            </div>

            {{-- Mode Tabs --}}
            <div class="relative flex items-center gap-1.5 px-5 py-3 border-b border-surface-200/50 dark:border-surface-700/50 bg-surface-50/80 dark:bg-surface-800/30 overflow-x-auto scrollbar-hide">
                <template x-for="(m, idx) in modes" :key="m.id">
                    <button @click="switchMode(m.id)"
                            :class="{ 
                                'bg-theme-gradient text-white shadow-lg shadow-theme-500/30 scale-105': mode === m.id,
                                'text-surface-600 dark:text-surface-400 hover:text-surface-900 dark:hover:text-surface-200 hover:bg-surface-200/80 dark:hover:bg-surface-700/50 hover:scale-102': mode !== m.id
                            }"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 ease-out whitespace-nowrap transform">
                        <i :data-lucide="m.icon" class="w-4 h-4 transition-transform duration-300" :class="mode === m.id ? 'scale-110' : ''"></i>
                        <span x-text="m.label"></span>
                    </button>
                </template>
            </div>

            {{-- Results Container with Tab Transition --}}
            <div class="max-h-[55vh] overflow-y-auto overscroll-contain">
                <div class="cp-tab-content"
                     x-ref="tabContent"
                     :class="isTabTransitioning ? 'cp-tab-exit' : 'cp-tab-enter'">
                    @include('partials.command-palette.results')
                </div>
            </div>

            {{-- Footer --}}
            <div class="relative flex items-center justify-between px-6 py-4 border-t border-surface-200/50 dark:border-surface-700/50 bg-surface-50/80 dark:bg-surface-800/30">
                <div class="flex items-center gap-5 text-xs text-surface-500 dark:text-surface-400">
                    <div class="flex items-center gap-1.5">
                        <kbd class="px-2 py-1 bg-surface-200/80 dark:bg-surface-700/80 rounded-md text-surface-600 dark:text-surface-300 font-semibold shadow-sm">↑</kbd>
                        <kbd class="px-2 py-1 bg-surface-200/80 dark:bg-surface-700/80 rounded-md text-surface-600 dark:text-surface-300 font-semibold shadow-sm">↓</kbd>
                        <span class="ml-1">navigasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <kbd class="px-2 py-1 bg-surface-200/80 dark:bg-surface-700/80 rounded-md text-surface-600 dark:text-surface-300 font-semibold shadow-sm">↵</kbd>
                        <span class="ml-1">pilih</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <kbd class="px-2 py-1 bg-surface-200/80 dark:bg-surface-700/80 rounded-md text-surface-600 dark:text-surface-300 font-semibold shadow-sm">Tab</kbd>
                        <span class="ml-1">ganti mode</span>
                    </div>
                </div>
                <div class="text-xs font-medium">
                    <span x-show="totalResults > 0" x-text="totalResults + ' hasil'" class="text-theme-500"></span>
                    <span x-show="calculatorResult !== null" class="text-theme-500 flex items-center gap-1">
                        <i data-lucide="calculator" class="w-3 h-3"></i>
                        Kalkulator aktif
                    </span>
                </div>
            </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Processing Modal for Cache Clear --}}
    <div x-show="isProcessing"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[200] flex items-center justify-center overflow-hidden"
         x-cloak>
    
    {{-- Animated Background with Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-surface-950/80 via-surface-900/70 to-theme-950/80 dark:from-black/90 dark:via-surface-950/80 dark:to-theme-950/90 backdrop-blur-md"></div>
    
    {{-- Floating Particles Animation --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="cache-particle cache-particle-1"></div>
        <div class="cache-particle cache-particle-2"></div>
        <div class="cache-particle cache-particle-3"></div>
        <div class="cache-particle cache-particle-4"></div>
        <div class="cache-particle cache-particle-5"></div>
        <div class="cache-particle cache-particle-6"></div>
        <div class="cache-particle cache-particle-7"></div>
        <div class="cache-particle cache-particle-8"></div>
    </div>
    
    {{-- Modal Content --}}
    <div x-show="isProcessing"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 scale-90 translate-y-8"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="relative bg-white/95 dark:bg-surface-800/95 backdrop-blur-xl rounded-3xl p-8 shadow-2xl shadow-theme-500/20 dark:shadow-theme-500/10 flex flex-col items-center gap-6 max-w-md mx-4 border border-white/20 dark:border-surface-700/50">
        
        {{-- Glow Effect --}}
        <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-theme-500/5 to-transparent pointer-events-none"></div>
        
        {{-- Animated Icon Container --}}
        <div class="relative">
            {{-- Outer Ring Animation --}}
            <div class="absolute inset-[-8px] rounded-full border-2 border-theme-500/20 animate-cache-ring-1"></div>
            <div class="absolute inset-[-16px] rounded-full border border-theme-400/10 animate-cache-ring-2"></div>
            <div class="absolute inset-[-24px] rounded-full border border-theme-300/5 animate-cache-ring-3"></div>
            
            {{-- Main Icon Circle --}}
            <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-theme-500 to-theme-600 flex items-center justify-center shadow-lg shadow-theme-500/30">
                {{-- Sweep Effect --}}
                <div class="absolute inset-0 rounded-full overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-cache-sweep"></div>
                </div>
                
                {{-- Icon --}}
                <i data-lucide="trash-2" class="w-10 h-10 text-white animate-cache-bounce"></i>
            </div>
            
            {{-- Pulse Effect --}}
            <div class="absolute inset-0 rounded-full bg-theme-500/20 animate-cache-pulse"></div>
        </div>
        
        {{-- Text Content --}}
        <div class="text-center relative z-10">
            <h3 class="text-xl font-bold text-surface-900 dark:text-white flex items-center justify-center gap-2" x-text="processingTitle">
            </h3>
            <p class="text-sm text-surface-600 dark:text-surface-400 mt-2 max-w-xs" x-text="processingMessage"></p>
        </div>
        
        {{-- Progress Steps --}}
        <div class="flex items-center gap-3 text-xs font-medium">
            <div class="flex items-center gap-1.5 text-theme-500 cache-step-active">
                <div class="w-2 h-2 rounded-full bg-theme-500 animate-pulse"></div>
                <span>LocalStorage</span>
            </div>
            <div class="w-4 h-px bg-surface-300 dark:bg-surface-600"></div>
            <div class="flex items-center gap-1.5 text-surface-400 dark:text-surface-500 cache-step" style="animation-delay: 0.5s;">
                <div class="w-2 h-2 rounded-full bg-surface-300 dark:bg-surface-600"></div>
                <span>SessionStorage</span>
            </div>
            <div class="w-4 h-px bg-surface-300 dark:bg-surface-600"></div>
            <div class="flex items-center gap-1.5 text-surface-400 dark:text-surface-500 cache-step" style="animation-delay: 1s;">
                <div class="w-2 h-2 rounded-full bg-surface-300 dark:bg-surface-600"></div>
                <span>Reload</span>
            </div>
        </div>
        
        {{-- Enhanced Progress Bar --}}
        <div class="w-full relative">
            <div class="h-2 bg-surface-200 dark:bg-surface-700 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-theme-400 via-theme-500 to-theme-600 rounded-full animate-cache-progress relative">
                    {{-- Shine Effect --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-cache-shine"></div>
                </div>
            </div>
            {{-- Progress Glow --}}
            <div class="absolute inset-0 h-2 rounded-full bg-theme-500/20 blur-sm animate-cache-progress-glow"></div>
        </div>
        
        {{-- Bottom Hint --}}
        <p class="text-xs text-surface-400 dark:text-surface-500 flex items-center gap-1.5">
            <i data-lucide="info" class="w-3 h-3"></i>
            Halaman akan dimuat ulang secara otomatis
        </p>
    </div>
</div>

</div>

{{-- Fullscreen Transition Overlay --}}
<div id="fullscreen-overlay"
     class="fixed inset-0 z-[300] pointer-events-none opacity-0 transition-opacity duration-300 bg-surface-900/20 dark:bg-black/30"
     style="display: none;"></div>

<style>
    /* Progress Indeterminate */
    @keyframes progress-indeterminate {
        0% { transform: translateX(-100%); width: 30%; }
        50% { transform: translateX(100%); width: 50%; }
        100% { transform: translateX(300%); width: 30%; }
    }
    .animate-progress-indeterminate {
        animation: progress-indeterminate 1.5s ease-in-out infinite;
    }
    
    /* Cache Clear Modal Animations */
    @keyframes cache-sweep {
        0% { transform: translateX(-100%) skewX(-15deg); }
        100% { transform: translateX(200%) skewX(-15deg); }
    }
    .animate-cache-sweep {
        animation: cache-sweep 1.5s ease-in-out infinite;
    }
    
    @keyframes cache-bounce {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-3px) scale(1.05); }
    }
    .animate-cache-bounce {
        animation: cache-bounce 1s ease-in-out infinite;
    }
    
    @keyframes cache-pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.15); opacity: 0; }
    }
    .animate-cache-pulse {
        animation: cache-pulse 1.5s ease-in-out infinite;
    }
    
    @keyframes cache-ring-1 {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.2; }
    }
    .animate-cache-ring-1 {
        animation: cache-ring-1 2s ease-in-out infinite;
    }
    
    @keyframes cache-ring-2 {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.15); opacity: 0.1; }
    }
    .animate-cache-ring-2 {
        animation: cache-ring-2 2.5s ease-in-out infinite 0.3s;
    }
    
    @keyframes cache-ring-3 {
        0%, 100% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.2); opacity: 0; }
    }
    .animate-cache-ring-3 {
        animation: cache-ring-3 3s ease-in-out infinite 0.6s;
    }
    
    @keyframes cache-progress {
        0% { width: 0%; }
        20% { width: 25%; }
        50% { width: 60%; }
        80% { width: 85%; }
        100% { width: 100%; }
    }
    .animate-cache-progress {
        animation: cache-progress 1.5s ease-out forwards;
    }
    
    @keyframes cache-shine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    .animate-cache-shine {
        animation: cache-shine 1s ease-in-out infinite;
    }
    
    @keyframes cache-progress-glow {
        0% { width: 0%; }
        100% { width: 100%; }
    }
    .animate-cache-progress-glow {
        animation: cache-progress-glow 1.5s ease-out forwards;
    }
    
    /* Floating Particles */
    .cache-particle {
        position: absolute;
        width: 6px;
        height: 6px;
        background: linear-gradient(135deg, var(--color-theme-400), var(--color-theme-500));
        border-radius: 50%;
        animation: cache-float 3s ease-in-out infinite;
    }
    
    @keyframes cache-float {
        0%, 100% { 
            transform: translateY(0) rotate(0deg) scale(1);
            opacity: 0.7;
        }
        33% { 
            transform: translateY(-30px) rotate(120deg) scale(0.8);
            opacity: 0.4;
        }
        66% { 
            transform: translateY(-15px) rotate(240deg) scale(1.1);
            opacity: 0.9;
        }
    }
    
    .cache-particle-1 { top: 20%; left: 15%; animation-delay: 0s; }
    .cache-particle-2 { top: 30%; right: 20%; animation-delay: 0.3s; }
    .cache-particle-3 { top: 60%; left: 10%; animation-delay: 0.6s; }
    .cache-particle-4 { top: 70%; right: 15%; animation-delay: 0.9s; }
    .cache-particle-5 { top: 40%; left: 25%; animation-delay: 1.2s; }
    .cache-particle-6 { top: 50%; right: 25%; animation-delay: 1.5s; }
    .cache-particle-7 { top: 15%; left: 40%; animation-delay: 1.8s; }
    .cache-particle-8 { top: 80%; left: 45%; animation-delay: 2.1s; }
    
    /* Step Animation */
    @keyframes cache-step-activate {
        0% { color: var(--color-surface-400); }
        100% { color: var(--color-theme-500); }
    }
    
    .cache-step {
        animation: cache-step-activate 0.3s ease-out forwards;
        animation-play-state: paused;
    }
    
    .cache-step-active .cache-step {
        animation-play-state: running;
    }
    
    /* Command Palette Tab Transitions */
    .cp-tab-content {
        transition: opacity 0.2s ease-out, transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .cp-tab-enter {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
    
    .cp-tab-exit {
        opacity: 0;
        transform: translateX(-8px) scale(0.98);
    }
    
    /* Scrollbar styling for command palette */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Staggered Item Animation */
    @keyframes slide-in-right {
        0% { opacity: 0; transform: translateX(-10px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    
    .animate-slide-in-right {
        animation: slide-in-right 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0; /* Star hidden */
    }
</style>
