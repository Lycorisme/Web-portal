{{-- Command Palette - Theme Mode (~60 lines) --}}

<template x-if="mode === 'theme' && !query">
    <div class="p-3">
        <div class="px-3 py-2 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="palette" class="w-3.5 h-3.5"></i>
            Pilih Tema
        </div>
        <div class="grid grid-cols-3 gap-2 mt-2">
            <template x-for="(t, index) in themes" :key="t.id">
                <button @click="setTheme(t.id)"
                        @mouseenter="selectedIndex = index"
                        :class="{ 
                            'ring-2 ring-theme-500 ring-offset-2 ring-offset-white dark:ring-offset-surface-900': currentTheme === t.id,
                            'hover:bg-surface-100 dark:hover:bg-surface-800': currentTheme !== t.id
                        }"
                        class="flex flex-col items-center gap-2 p-4 rounded-xl transition-all duration-150 border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800">
                    <div class="w-8 h-8 rounded-full shadow-md" :style="'background: linear-gradient(135deg, ' + t.from + ', ' + t.to + ')'"></div>
                    <span class="text-sm font-medium text-surface-700 dark:text-surface-300" x-text="t.label"></span>
                </button>
            </template>
        </div>
        

    </div>
</template>
