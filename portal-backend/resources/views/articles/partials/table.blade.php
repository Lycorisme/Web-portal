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
        <table class="w-full min-w-[1000px]">
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
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap min-w-[280px]">
                        Berita
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Kategori
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Status
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        <i data-lucide="eye" class="w-4 h-4 inline"></i>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        <i data-lucide="clock" class="w-4 h-4 inline"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Penulis
                    </th>
                    <th class="w-16 px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-100 dark:divide-surface-800">
                <template x-for="article in articles" :key="article.id">
                    <tr 
                        class="transition-colors border-b border-surface-100 dark:border-surface-800"
                        :class="article.deleted_at ? 'bg-rose-50/70 hover:bg-rose-100/70 dark:bg-rose-900/10 dark:hover:bg-rose-900/20' : 'hover:bg-surface-50 dark:hover:bg-surface-800/30'"
                    >
                        {{-- Checkbox --}}
                        <td class="px-4 py-3">
                            <input 
                                type="checkbox"
                                :value="article.id"
                                x-model="selectedIds"
                                class="w-4 h-4 rounded border-surface-300 dark:border-surface-600 text-theme-600 focus:ring-theme-500"
                            >
                        </td>

                        {{-- Article Title with Thumbnail --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                {{-- Thumbnail --}}
                                <div 
                                    class="w-14 h-10 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden bg-surface-100 dark:bg-surface-800"
                                >
                                    <template x-if="article.thumbnail">
                                        <img :src="article.thumbnail" :alt="article.title" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!article.thumbnail">
                                        <i data-lucide="image" class="w-5 h-5 text-surface-400"></i>
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span 
                                        class="text-sm font-semibold text-surface-900 dark:text-white block truncate max-w-[200px]" 
                                        :class="{'line-through opacity-60 text-rose-700 dark:text-rose-400': article.deleted_at}"
                                        x-text="article.title"
                                        :title="article.title"
                                    ></span>
                                    <span class="text-xs text-surface-500 block truncate max-w-[200px]" x-text="article.created_at_human"></span>
                                </div>
                            </div>
                        </td>

                        {{-- Category --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <template x-if="article.category_name">
                                <span 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :style="`background-color: ${article.category_color}20; color: ${article.category_color}`"
                                    :class="{'opacity-60 grayscale': article.deleted_at}"
                                >
                                    <i :data-lucide="article.category_icon || 'folder'" class="w-3 h-3"></i>
                                    <span x-text="article.category_name"></span>
                                </span>
                            </template>
                            <template x-if="!article.category_name">
                                <span class="text-xs text-surface-400">-</span>
                            </template>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span 
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                :class="[getStatusColor(article.status), {'opacity-60 grayscale': article.deleted_at}]"
                            >
                                <span x-text="getStatusLabel(article.status)"></span>
                            </span>
                        </td>

                        {{-- Views --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span 
                                class="text-sm font-medium text-surface-600 dark:text-surface-400"
                                :class="{'opacity-60': article.deleted_at}"
                                x-text="article.views || 0"
                            ></span>
                        </td>

                        {{-- Read Time --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span 
                                class="text-sm text-surface-600 dark:text-surface-400"
                                :class="{'opacity-60': article.deleted_at}"
                                x-text="(article.read_time || 1) + ' mnt'"
                            ></span>
                        </td>

                        {{-- Author --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    <template x-if="article.author_avatar">
                                        <img :src="article.author_avatar" :alt="article.author_name" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!article.author_avatar">
                                        <span class="text-xs font-semibold text-theme-600 dark:text-theme-400" x-text="(article.author_name || 'A').charAt(0).toUpperCase()"></span>
                                    </template>
                                </div>
                                <span 
                                    class="text-sm text-surface-700 dark:text-surface-300"
                                    :class="{'opacity-60': article.deleted_at}"
                                    x-text="article.author_name || 'Admin'"
                                ></span>
                            </div>
                        </td>

                        {{-- Actions (Kebab Menu) --}}
                        <td class="px-4 py-3 text-center relative">
                            <div class="relative inline-block kebab-menu-container">
                                <button 
                                    @click="openMenu(article, $event)"
                                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-700/50 rounded-lg transition-colors"
                                    :class="{'bg-surface-100 dark:bg-surface-700/50': activeMenuArticle?.id === article.id}"
                                >
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-surface-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Empty State --}}
                <template x-if="!loading && articles.length === 0">
                    <tr>
                        <td colspan="8" class="px-4 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="newspaper" class="w-8 h-8 text-surface-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-1">Tidak Ada Data</h3>
                                <p class="text-sm text-surface-500 dark:text-surface-400 mb-4">Belum ada berita yang tersedia.</p>
                                <button 
                                    @click="openCreateModal()"
                                    x-show="!showTrash"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-theme-gradient text-white font-medium text-sm rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20"
                                >
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    <span>Tambah Berita Pertama</span>
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
