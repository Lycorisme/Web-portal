{{-- Command Palette Results - Part 2: Results Templates (~220 lines) --}}

{{-- Calculator Result --}}
<template x-if="calculatorResult !== null">
    <div class="p-4 mx-3 mt-3 rounded-xl bg-theme-500/10 dark:bg-theme-500/20 border border-theme-500/20">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-theme-gradient flex items-center justify-center">
                    <i data-lucide="calculator" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <div class="text-sm text-surface-500 dark:text-surface-400">Hasil Kalkulasi</div>
                    <div class="text-2xl font-bold text-theme-600 dark:text-theme-400" x-text="calculatorResult"></div>
                </div>
            </div>
            <button @click="copyToClipboard(calculatorResult)" 
                    class="p-2 rounded-lg hover:bg-theme-500/20 transition-colors"
                    title="Salin hasil">
                <i data-lucide="copy" class="w-5 h-5 text-theme-500"></i>
            </button>
        </div>
    </div>
</template>

{{-- Navigation Mode --}}
<template x-if="mode === 'navigate' && !query">
    <div class="p-3">
        {{-- Quick Actions --}}
        <div class="px-3 py-2 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="compass" class="w-3.5 h-3.5"></i>
            Navigasi Cepat
        </div>
        <div class="space-y-1">
            <template x-for="(action, index) in quickActions" :key="action.url">
                <a :href="action.url"
                   @mouseenter="selectedIndex = index"
                   :class="{ 
                       'bg-theme-500/10 dark:bg-theme-500/20 border-l-4 border-theme-500 pl-3': selectedIndex === index,
                       'border-l-4 border-transparent': selectedIndex !== index
                   }"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 group hover:bg-surface-100 dark:hover:bg-surface-800">
                    <div :class="selectedIndex === index ? 'bg-theme-gradient shadow-lg shadow-theme-500/25' : 'bg-surface-200 dark:bg-surface-700'"
                         class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200">
                        <i :data-lucide="action.icon"
                           :class="selectedIndex === index ? 'text-white' : 'text-surface-600 dark:text-surface-300'"
                           class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div :class="selectedIndex === index ? 'text-theme-600 dark:text-theme-400' : 'text-surface-800 dark:text-white'" class="font-medium transition-colors" x-text="action.label"></div>
                        <div class="text-sm text-surface-500 dark:text-surface-400" x-text="action.description"></div>
                    </div>
                    <template x-if="action.shortcut">
                        <kbd class="hidden sm:inline-flex px-2 py-1 text-xs font-medium text-surface-500 bg-surface-100 dark:bg-surface-700 rounded border border-surface-300 dark:border-surface-600" x-text="action.shortcut"></kbd>
                    </template>
                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="corner-down-left" class="w-4 h-4 text-theme-500"></i>
                    </div>
                </a>
            </template>
        </div>

        {{-- Recent Pages --}}
        <template x-if="recentPages.length > 0">
            <div class="mt-4">
                <div class="flex items-center justify-between px-3 py-2">
                    <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        Halaman Terbaru
                    </span>
                    <button @click="clearRecentPages()" class="text-xs text-surface-400 hover:text-theme-500 transition-colors">
                        Hapus
                    </button>
                </div>
                <div class="space-y-1">
                    <template x-for="page in recentPages.slice(0, 5)" :key="page.url">
                        <a :href="page.url"
                           class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors text-left">
                            <i data-lucide="file-text" class="w-4 h-4 text-surface-400"></i>
                            <span class="text-surface-700 dark:text-surface-300 truncate" x-text="page.title"></span>
                            <span class="text-xs text-surface-400 dark:text-surface-500 ml-auto" x-text="page.time"></span>
                        </a>
                    </template>
                </div>
            </div>
        </template>

        {{-- Recent Searches --}}
        <template x-if="recentSearches.length > 0">
            <div class="mt-4">
                <div class="flex items-center justify-between px-3 py-2">
                    <span class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        Pencarian Terbaru
                    </span>
                    <button @click="clearRecentSearches()" class="text-xs text-surface-400 hover:text-theme-500 transition-colors">
                        Hapus Semua
                    </button>
                </div>
                <div class="space-y-1">
                    <template x-for="recent in recentSearches.slice(0, 5)" :key="recent">
                        <button @click="query = recent; handleInput()"
                                class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors text-left">
                            <i data-lucide="history" class="w-4 h-4 text-surface-400"></i>
                            <span class="text-surface-700 dark:text-surface-300" x-text="recent"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

{{-- Create Mode --}}
<template x-if="mode === 'create' && !query">
    <div class="p-3">
        <div class="px-3 py-2 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
            Buat Baru
        </div>
        <div class="space-y-1">
            <template x-for="(action, index) in createActions" :key="action.url">
                <a :href="action.url"
                   @mouseenter="selectedIndex = index"
                   :class="{ 
                       'bg-theme-500/10 dark:bg-theme-500/20 border-l-4 border-theme-500 pl-3': selectedIndex === index,
                       'border-l-4 border-transparent': selectedIndex !== index
                   }"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 group hover:bg-surface-100 dark:hover:bg-surface-800">
                    <div :class="selectedIndex === index ? 'bg-theme-gradient shadow-lg shadow-theme-500/25' : 'bg-surface-200 dark:bg-surface-700'"
                         class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200">
                        <i :data-lucide="action.icon"
                           :class="selectedIndex === index ? 'text-white' : 'text-surface-600 dark:text-surface-300'"
                           class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div :class="selectedIndex === index ? 'text-theme-600 dark:text-theme-400' : 'text-surface-800 dark:text-white'" class="font-medium transition-colors" x-text="action.label"></div>
                        <div class="text-sm text-surface-500 dark:text-surface-400" x-text="action.description"></div>
                    </div>
                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="corner-down-left" class="w-4 h-4 text-theme-500"></i>
                    </div>
                </a>
            </template>
        </div>
    </div>
</template>

{{-- Commands Mode --}}
@include('partials.command-palette.commands-mode')

{{-- Theme Mode --}}
@include('partials.command-palette.theme-mode')

{{-- Search Results --}}
@include('partials.command-palette.search-results')

{{-- No Results --}}
<template x-if="query && results.length === 0 && !isLoading && hasSearched && calculatorResult === null">
    <div class="p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
            <i data-lucide="search-x" class="w-8 h-8 text-surface-400"></i>
        </div>
        <p class="text-surface-700 dark:text-surface-300 font-medium">Tidak ada hasil untuk "<span x-text="query" class="text-theme-500"></span>"</p>
        <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">Coba kata kunci yang berbeda</p>
    </div>
</template>
