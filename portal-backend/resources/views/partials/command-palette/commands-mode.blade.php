{{-- Command Palette - Commands Mode (~80 lines) --}}

<template x-if="mode === 'commands' && !query">
    <div class="p-3">
        <div class="px-3 py-2 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="terminal" class="w-3.5 h-3.5"></i>
            Perintah Sistem
        </div>
        <div class="space-y-1">
            <template x-for="(cmd, index) in systemCommands" :key="cmd.id">
                <button @click="executeCommand(cmd)"
                        @mouseenter="selectedIndex = index"
                        :class="{ 
                            'bg-theme-500/10 dark:bg-theme-500/20 border-l-4 border-theme-500 pl-3': selectedIndex === index,
                            'border-l-4 border-transparent': selectedIndex !== index
                        }"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 group hover:bg-surface-100 dark:hover:bg-surface-800 w-full text-left">
                    <div :class="selectedIndex === index ? 'bg-theme-gradient shadow-lg shadow-theme-500/25' : 'bg-surface-200 dark:bg-surface-700'"
                         class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200">
                        {{-- Dynamic icon based on dark mode for toggle-dark command --}}
                        <i :data-lucide="cmd.isDynamic ? (isDarkMode ? cmd.icon : cmd.iconAlt) : cmd.icon"
                           :class="selectedIndex === index ? 'text-white' : 'text-surface-600 dark:text-surface-300'"
                           class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        {{-- Dynamic label based on dark mode for toggle-dark command --}}
                        <div :class="selectedIndex === index ? 'text-theme-600 dark:text-theme-400' : 'text-surface-800 dark:text-white'" 
                             class="font-medium transition-colors" 
                             x-text="cmd.isDynamic ? (isDarkMode ? cmd.label : cmd.labelAlt) : cmd.label"></div>
                        {{-- Dynamic description based on dark mode for toggle-dark command --}}
                        <div class="text-sm text-surface-500 dark:text-surface-400" 
                             x-text="cmd.isDynamic ? (isDarkMode ? cmd.description : cmd.descriptionAlt) : cmd.description"></div>
                    </div>
                    <template x-if="cmd.shortcut">
                        <kbd class="hidden sm:inline-flex px-2 py-1 text-xs font-medium text-surface-500 bg-surface-100 dark:bg-surface-700 rounded border border-surface-300 dark:border-surface-600" x-text="cmd.shortcut"></kbd>
                    </template>
                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="corner-down-left" class="w-4 h-4 text-theme-500"></i>
                    </div>
                </button>
            </template>
        </div>
    </div>
</template>
