{{-- 
    Lockout Countdown Modal Component - Fullscreen Centered
    ========================================================
    Modal fullscreen yang muncul saat akun terkunci.
    - Fixed position (tidak terpengaruh scroll)
    - Perfectly centered
    - Responsive - tidak terpotong
    - Smooth enter/leave animations
--}}

<template x-teleport="body">
    <div x-show="isLocked" 
         x-cloak
         class="fixed inset-0 z-[99999] overflow-y-auto overflow-x-hidden"
         style="position: fixed !important;"
         @keydown.escape.prevent
         x-transition:enter="transition-all ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-all ease-in duration-400"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        {{-- Fullscreen Background --}}
        <div class="fixed inset-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
            {{-- Subtle gradient overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-red-950/30 via-transparent to-amber-950/20"></div>
            
            {{-- Subtle grid pattern --}}
            <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        {{-- Content Wrapper - Centered --}}
        <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
            
            {{-- Main Card --}}
            <div class="w-full max-w-md"
                 x-show="isLocked"
                 x-transition:enter="transition-all ease-out duration-600 delay-100"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition-all ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                
                {{-- Glass Card --}}
                <div class="bg-slate-900/70 backdrop-blur-xl rounded-3xl border border-slate-700/50 shadow-2xl shadow-red-500/10 overflow-hidden">
                    
                    {{-- Top Accent Bar --}}
                    <div class="h-1 bg-gradient-to-r from-red-500 via-red-400 to-amber-500"></div>
                    
                    <div class="p-8 md:p-10">
                        
                        {{-- Lock Icon --}}
                        <div class="flex justify-center mb-6">
                            <div class="relative"
                                 x-transition:enter="transition-all ease-out duration-700 delay-200"
                                 x-transition:enter-start="opacity-0 scale-0 rotate-180"
                                 x-transition:enter-end="opacity-100 scale-100 rotate-0">
                                
                                {{-- Icon Container --}}
                                <div class="w-20 h-20 bg-gradient-to-br from-red-500/20 to-red-600/10 rounded-full flex items-center justify-center border border-red-500/30 relative">
                                    <svg class="w-9 h-9 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    
                                    {{-- Rotating ring --}}
                                    <div class="absolute inset-0 rounded-full border-2 border-transparent border-t-red-500/60 animate-spin" style="animation-duration: 3s;"></div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Title --}}
                        <h2 class="text-2xl md:text-3xl font-bold text-white text-center mb-2">
                            Akun Terkunci
                        </h2>
                        
                        <p class="text-slate-400 text-center text-sm mb-8">
                            Terlalu banyak percobaan login gagal.<br>
                            Silakan tunggu hingga waktu berakhir.
                        </p>
                        
                        {{-- Timer Section --}}
                        <div class="bg-slate-800/50 rounded-2xl p-6 border border-slate-700/30 mb-6">
                            <p class="text-xs text-slate-500 uppercase tracking-widest text-center mb-5 font-medium">Waktu Tersisa</p>
                            
                            {{-- Timer Display --}}
                            <div class="flex items-center justify-center gap-4">
                                {{-- Minutes --}}
                                <div class="bg-slate-900/80 rounded-xl px-5 py-4 min-w-[85px] text-center border border-slate-700/50">
                                    <span class="text-4xl md:text-5xl font-bold text-white font-mono" 
                                          x-text="String(Math.floor(countdown / 60)).padStart(2, '0')">00</span>
                                    <p class="text-xs text-slate-500 mt-2 uppercase tracking-wider">Menit</p>
                                </div>
                                
                                {{-- Separator --}}
                                <div class="flex flex-col gap-2">
                                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></span>
                                </div>
                                
                                {{-- Seconds --}}
                                <div class="bg-slate-900/80 rounded-xl px-5 py-4 min-w-[85px] text-center border border-slate-700/50">
                                    <span class="text-4xl md:text-5xl font-bold text-white font-mono" 
                                          x-text="String(countdown % 60).padStart(2, '0')">00</span>
                                    <p class="text-xs text-slate-500 mt-2 uppercase tracking-wider">Detik</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="relative h-2.5 bg-slate-800 rounded-full overflow-hidden mb-6">
                            <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-red-500 to-amber-500 rounded-full transition-all duration-1000 ease-linear"
                                 :style="`width: ${progressPercent}%`">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/25 to-transparent animate-shimmer"></div>
                            </div>
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex items-center justify-center gap-2 text-slate-500">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs">Halaman akan terbuka otomatis setelah waktu berakhir</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

{{-- Animations --}}
<style>
    [x-cloak] { display: none !important; }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
</style>
