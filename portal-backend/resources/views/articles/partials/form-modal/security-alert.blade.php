{{-- SECURITY ALERT PANEL - Shown when threats detected --}}
<template x-if="injectionDetected">
    <div class="rounded-xl sm:rounded-2xl border-2 border-rose-300 dark:border-rose-700 bg-gradient-to-br from-rose-50 to-orange-50 dark:from-rose-900/30 dark:to-orange-900/20 overflow-hidden shadow-lg shadow-rose-500/10">
        {{-- Alert Header --}}
        <div class="bg-rose-500 dark:bg-rose-600 px-3 py-2 sm:px-4 sm:py-3 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <div class="p-1.5 sm:p-2 bg-white/20 rounded-lg flex-shrink-0">
                    <i data-lucide="shield-x" class="w-4 h-4 sm:w-5 sm:h-5 text-white"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="text-xs sm:text-sm font-bold text-white truncate">⚠️ Konten Berbahaya!</h4>
                    <p class="text-[10px] sm:text-xs text-rose-100 hidden sm:block">Potensi serangan injeksi terdeteksi</p>
                </div>
            </div>
            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-white/20 text-white text-[10px] sm:text-xs font-bold rounded-full flex-shrink-0" x-text="detectedThreats.length + ' ancaman'"></span>
        </div>
        
        {{-- Threat List (Collapsible on Mobile) --}}
        <div class="p-2 sm:p-4 space-y-2 sm:space-y-3 max-h-32 sm:max-h-48 overflow-y-auto">
            <template x-for="(threat, index) in detectedThreats.slice(0, 3)" :key="index">
                <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl border-l-4 transition-all" :class="getSeverityBorderColor(threat.severity)">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full text-[9px] sm:text-[10px] font-bold" :class="getSeverityColor(threat.severity)" x-text="index + 1"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                            <span class="text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 rounded-full" :class="getSeverityColor(threat.severity)" x-text="threat.category"></span>
                            <code class="text-[9px] sm:text-[10px] px-1.5 sm:px-2 py-0.5 bg-surface-200 dark:bg-surface-700 text-rose-600 dark:text-rose-400 rounded font-mono truncate max-w-[100px] sm:max-w-none" x-text="threat.keyword"></code>
                        </div>
                        <p class="text-[10px] sm:text-xs text-surface-600 dark:text-surface-400 mt-0.5 sm:mt-1 line-clamp-1 sm:line-clamp-none" x-text="threat.description"></p>
                    </div>
                </div>
            </template>
            <template x-if="detectedThreats.length > 3">
                <p class="text-[10px] sm:text-xs text-center text-surface-500 py-1">+ <span x-text="detectedThreats.length - 3"></span> ancaman lainnya</p>
            </template>
        </div>
        
        {{-- Action Buttons --}}
        <div class="px-2 py-2 sm:px-4 sm:py-3 bg-surface-100 dark:bg-surface-800/50 border-t border-rose-200 dark:border-rose-800 flex gap-2">
            <button 
                type="button"
                @click="previewSanitization()"
                class="flex-1 flex items-center justify-center gap-1 sm:gap-2 px-2 py-2 sm:px-4 sm:py-2.5 bg-white dark:bg-surface-800 border border-surface-300 dark:border-surface-600 text-surface-700 dark:text-surface-300 rounded-lg sm:rounded-xl text-[10px] sm:text-sm font-semibold hover:bg-surface-50 dark:hover:bg-surface-700 transition-all"
            >
                <i data-lucide="eye" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                <span class="hidden sm:inline">Preview</span>
            </button>
            <button 
                type="button"
                @click="applySanitization()"
                class="flex-1 flex items-center justify-center gap-1 sm:gap-2 px-2 py-2 sm:px-4 sm:py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg sm:rounded-xl text-[10px] sm:text-sm font-bold shadow-md shadow-emerald-500/20 transition-all"
            >
                <i data-lucide="shield-check" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                <span>Bersihkan</span>
            </button>
        </div>
    </div>
</template>
