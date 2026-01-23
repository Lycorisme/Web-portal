{{-- Command Palette - Search Results (~80 lines) --}}

<template x-if="query && results.length > 0">
    <div class="p-3">
        <template x-for="(group, groupIndex) in results" :key="group.type">
            <div class="mb-4 last:mb-0">
                <div class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                    <i :data-lucide="group.icon" class="w-3.5 h-3.5"></i>
                    <span x-text="group.label"></span>
                    <span class="text-surface-400 dark:text-surface-500" x-text="'(' + group.items.length + ')'"></span>
                </div>
                <div class="space-y-1">
                    <template x-for="(item, itemIndex) in group.items" :key="item.id">
                        <a :href="item.url"
                           @mouseenter="setSelectedByFlatIndex(groupIndex, itemIndex)"
                           :class="{ 
                               'bg-theme-500/10 dark:bg-theme-500/20 border-l-4 border-theme-500 pl-3': isItemSelected(groupIndex, itemIndex),
                               'border-l-4 border-transparent': !isItemSelected(groupIndex, itemIndex)
                           }"
                           class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-all group">

                            {{-- Thumbnail/Icon --}}
                            <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-surface-100 dark:bg-surface-800">
                                <template x-if="item.image">
                                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.image && item.color">
                                    <div class="w-full h-full flex items-center justify-center" :style="'background-color: ' + item.color">
                                        <i :data-lucide="group.icon" class="w-5 h-5 text-white"></i>
                                    </div>
                                </template>
                                <template x-if="!item.image && !item.color">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i :data-lucide="group.icon" class="w-5 h-5 text-surface-400"></i>
                                    </div>
                                </template>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div :class="isItemSelected(groupIndex, itemIndex) ? 'text-theme-600 dark:text-theme-400' : 'text-surface-800 dark:text-white'" class="font-medium truncate transition-colors" x-text="item.title"></div>
                                <div class="text-sm text-surface-500 dark:text-surface-400 truncate" x-text="item.subtitle"></div>
                            </div>

                            {{-- Badge --}}
                            <template x-if="item.badge">
                                <span :class="{
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': item.badge.color === 'emerald',
                                    'bg-slate-100 text-slate-700 dark:bg-slate-900/30 dark:text-slate-400': item.badge.color === 'slate',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': item.badge.color === 'amber',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400': item.badge.color === 'rose',
                                    'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400': item.badge.color === 'violet',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': item.badge.color === 'blue',
                                }" class="px-2.5 py-1 text-xs font-medium rounded-lg flex-shrink-0" x-text="item.badge.label">
                                </span>
                            </template>

                            {{-- Arrow --}}
                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="arrow-right" class="w-4 h-4 text-theme-500"></i>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>
