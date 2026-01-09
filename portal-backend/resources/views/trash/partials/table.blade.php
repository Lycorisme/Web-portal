{{-- Table Section --}}
<div class="relative" style="overflow: visible;">
    {{-- Loading Overlay --}}
    <div 
        x-show="loading"
        x-transition
        class="absolute inset-0 bg-white/80 dark:bg-surface-900/80 backdrop-blur-sm flex items-center justify-center z-20"
    >
        <div class="flex flex-col items-center gap-3">
            <div class="w-10 h-10 border-4 border-theme-500/30 border-t-theme-500 rounded-full animate-spin"></div>
            <span class="text-sm text-surface-600 dark:text-surface-400">Memuat data...</span>
        </div>
    </div>

    {{-- Table Container with Horizontal Scroll --}}
    <div class="table-scroll-container overflow-x-auto" style="overflow-y: visible;">
        <table class="w-full min-w-[700px]">
            <thead>
                <tr class="bg-surface-50 dark:bg-surface-800/50">
                    <th class="w-12 px-4 py-3 text-left">
                        <input 
                            type="checkbox"
                            x-model="selectAll"
                            @change="toggleSelectAll()"
                            class="w-4 h-4 rounded border-surface-300 dark:border-surface-600 text-theme-600 focus:ring-theme-500"
                        >
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Tipe
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Nama
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Detail
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Dihapus
                    </th>
                    <th class="w-16 px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-100 dark:divide-surface-800">
                <template x-for="item in items" :key="item.type + '-' + item.id">
                    <tr class="transition-colors border-b border-surface-100 dark:border-surface-800 bg-theme-50/50 hover:bg-theme-100/50 dark:bg-theme-900/10 dark:hover:bg-theme-900/20">
                        {{-- Checkbox --}}
                        <td class="px-4 py-3">
                            <input 
                                type="checkbox"
                                :value="JSON.stringify({type: item.type, id: item.id})"
                                x-model="selectedItems"
                                class="w-4 h-4 rounded border-surface-300 dark:border-surface-600 text-theme-600 focus:ring-theme-500"
                            >
                        </td>

                        {{-- Type Badge --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-surface-100 text-surface-700 dark:bg-surface-700 dark:text-surface-300">
                                <i :data-lucide="item.type_icon" class="w-3 h-3"></i>
                                <span x-text="item.type_label"></span>
                            </span>
                        </td>

                        {{-- Name --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                {{-- Thumbnail for galleries --}}
                                <template x-if="item.type === 'gallery' && item.thumbnail">
                                    <img :src="item.thumbnail" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                </template>
                                {{-- Avatar for users --}}
                                <template x-if="item.type === 'user' && item.avatar">
                                    <img :src="item.avatar" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                </template>
                                {{-- Icon for others --}}
                                <template x-if="!(item.type === 'gallery' && item.thumbnail) && !(item.type === 'user' && item.avatar)">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm bg-theme-100 dark:bg-theme-900/30">
                                        <i :data-lucide="item.type_icon" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                                    </div>
                                </template>
                                <div>
                                    <span class="text-sm font-semibold text-surface-900 dark:text-white block line-through opacity-70" x-text="item.name"></span>
                                    <span class="text-xs text-surface-500" x-text="item.subtitle"></span>
                                </div>
                            </div>
                        </td>

                        {{-- Extra Info --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm text-surface-600 dark:text-surface-400" x-text="item.extra"></span>
                        </td>

                        {{-- Deleted At --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm text-surface-600 dark:text-surface-400" x-text="item.deleted_at"></span>
                                <span class="text-xs text-surface-400" x-text="item.deleted_at_human"></span>
                            </div>
                        </td>

                        {{-- Actions (Kebab Menu) --}}
                        <td class="px-4 py-3 text-center relative">
                            <div class="relative inline-block kebab-menu-container">
                                <button 
                                    @click="openMenu(item, $event)"
                                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-700/50 rounded-lg transition-colors"
                                    :class="{'bg-surface-100 dark:bg-surface-700/50': activeMenuItem?.id === item.id && activeMenuItem?.type === item.type}"
                                >
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-surface-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                <template x-if="!loading && items.length === 0">
                    <tr>
                        <td colspan="6" class="px-4 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="trash-2" class="w-8 h-8 text-surface-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-1">Tong Sampah Kosong</h3>
                                <p class="text-sm text-surface-500 dark:text-surface-400">Tidak ada item yang dihapus.</p>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

{{-- Add CSS to fix overflow issues --}}
<style>
    .table-scroll-container {
        position: relative;
    }
    .kebab-menu-container {
        position: static;
    }
    .kebab-menu-container > div[x-show] {
        position: absolute;
    }
    tr:last-child .kebab-menu-container > div[x-show],
    tr:nth-last-child(2) .kebab-menu-container > div[x-show] {
        top: auto;
        bottom: 100%;
        margin-top: 0;
        margin-bottom: 0.25rem;
    }
</style>
