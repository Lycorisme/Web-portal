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
        <table class="w-full min-w-[600px]">
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
                        Tag
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Slug
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Artikel
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Dibuat
                    </th>
                    <th class="w-16 px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-100 dark:divide-surface-800">
                <template x-for="tag in tags" :key="tag.id">
                    <tr 
                        class="transition-colors border-b border-surface-100 dark:border-surface-800"
                        :class="tag.deleted_at ? 'bg-rose-50/70 hover:bg-rose-100/70 dark:bg-rose-900/10 dark:hover:bg-rose-900/20' : 'hover:bg-surface-50 dark:hover:bg-surface-800/30'"
                    >
                        {{-- Checkbox --}}
                        <td class="px-4 py-3">
                            <input 
                                type="checkbox"
                                :value="tag.id"
                                x-model="selectedIds"
                                class="w-4 h-4 rounded border-surface-300 dark:border-surface-600 text-theme-600 focus:ring-theme-500"
                            >
                        </td>

                        {{-- Tag Name --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm bg-theme-100 dark:bg-theme-900/30">
                                    <i data-lucide="hash" class="w-5 h-5 text-theme-600 dark:text-theme-400"></i>
                                </div>
                                <div>
                                    <span 
                                        class="text-sm font-semibold text-surface-900 dark:text-white block" 
                                        :class="{'line-through opacity-60 text-rose-700 dark:text-rose-400': tag.deleted_at}"
                                        x-text="tag.name"
                                    ></span>
                                    <span class="text-xs text-surface-500" x-text="tag.created_at_human"></span>
                                </div>
                            </div>
                        </td>

                        {{-- Slug --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <code 
                                class="text-xs bg-surface-100 dark:bg-surface-800 px-2 py-1 rounded font-mono text-surface-700 dark:text-surface-300"
                                :class="{'line-through opacity-60': tag.deleted_at}"
                                x-text="tag.slug"
                            ></code>
                        </td>

                        {{-- Articles Count --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span 
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400"
                                :class="{'opacity-60 grayscale': tag.deleted_at}"
                            >
                                <i data-lucide="file-text" class="w-3 h-3"></i>
                                <span x-text="tag.articles_count"></span>
                            </span>
                        </td>

                        {{-- Created At --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm text-surface-600 dark:text-surface-400" x-text="tag.created_at"></span>
                        </td>

                        {{-- Actions (Kebab Menu) --}}
                        <td class="px-4 py-3 text-center relative">
                            <div class="relative inline-block kebab-menu-container">
                                <button 
                                    @click="openMenu(tag, $event)"
                                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-700/50 rounded-lg transition-colors"
                                    :class="{'bg-surface-100 dark:bg-surface-700/50': activeMenuTag?.id === tag.id}"
                                >
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-surface-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Empty State --}}
                <template x-if="!loading && tags.length === 0">
                    <tr>
                        <td colspan="6" class="px-4 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="tags" class="w-8 h-8 text-surface-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-1">Tidak Ada Data</h3>
                                <p class="text-sm text-surface-500 dark:text-surface-400 mb-4">Belum ada tag yang tersedia.</p>
                                <button 
                                    @click="openCreateModal()"
                                    x-show="!showTrash"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-theme-gradient text-white font-medium text-sm rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20"
                                >
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    <span>Tambah Tag Pertama</span>
                                </button>
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
