{{-- Table Section --}}
<div class="relative pt-4" style="overflow: visible;">
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
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        User
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Waktu
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Aksi
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap min-w-[250px]">
                        Deskripsi
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Level
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        IP Address
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Subject
                    </th>
                    <th class="w-16 px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-100 dark:divide-surface-800">
                <template x-for="log in logs" :key="log.id">
                    <tr 
                        class="transition-colors border-b border-surface-100 dark:border-surface-800"
                        :class="log.deleted_at ? 'bg-rose-50/70 hover:bg-rose-100/70 dark:bg-rose-900/10 dark:hover:bg-rose-900/20' : 'hover:bg-surface-50 dark:hover:bg-surface-800/30'"
                    >
                        {{-- Checkbox --}}
                        <td class="px-4 py-3">
                            <input 
                                type="checkbox"
                                :value="log.id"
                                x-model="selectedIds"
                                class="w-4 h-4 rounded border-surface-300 dark:border-surface-600 text-theme-600 focus:ring-theme-500"
                            >
                        </td>

                        {{-- User (moved first) --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <template x-if="log.user_avatar">
                                    <img :src="log.user_avatar" :alt="log.user_name" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                </template>
                                <template x-if="!log.user_avatar">
                                    <div class="w-8 h-8 rounded-full bg-theme-gradient flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-bold" x-text="log.user_name.charAt(0).toUpperCase()"></span>
                                    </div>
                                </template>
                                <span 
                                    class="text-sm font-medium text-surface-900 dark:text-white" 
                                    :class="{'line-through opacity-60 text-rose-700 dark:text-rose-400': log.deleted_at}"
                                    x-text="log.user_name"
                                ></span>
                            </div>
                        </td>

                        {{-- Waktu (moved second) --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span 
                                    class="text-sm font-medium text-surface-900 dark:text-white" 
                                    :class="{'line-through opacity-60 text-rose-700 dark:text-rose-400': log.deleted_at}"
                                    x-text="log.created_at"
                                ></span>
                                <span class="text-xs text-surface-500" x-text="log.created_at_human"></span>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span 
                            <span 
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                :class="[getActionBadgeClass(log.action), {'opacity-60 grayscale': log.deleted_at}]"
                                x-text="log.action_label"
                            ></span>
                        </td>

                        {{-- Deskripsi --}}
                        <td class="px-4 py-3">
                            <p 
                                class="text-sm text-surface-700 dark:text-surface-300 line-clamp-2 max-w-xs" 
                                :class="{'line-through opacity-60 text-rose-700 dark:text-rose-400': log.deleted_at}"
                                x-text="log.description"
                            ></p>
                        </td>

                        {{-- Level --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span 
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize"
                                :class="getLevelBadgeClass(log.level)"
                                x-text="log.level"
                            ></span>
                        </td>

                        {{-- IP Address --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <code class="text-xs bg-surface-100 dark:bg-surface-800 px-2 py-1 rounded font-mono text-surface-700 dark:text-surface-300" x-text="log.ip_address || '-'"></code>
                        </td>

                        {{-- Subject --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <template x-if="log.subject_type">
                                <div class="flex flex-col">
                                    <span class="text-xs text-surface-500" x-text="log.subject_type"></span>
                                    <span class="text-xs font-mono text-surface-700 dark:text-surface-300" x-text="'#' + log.subject_id"></span>
                                </div>
                            </template>
                            <template x-if="!log.subject_type">
                                <span class="text-xs text-surface-400">-</span>
                            </template>
                        </td>

                        {{-- Actions (Kebab Menu) - Simplified approach --}}
                        <td class="px-4 py-3 text-center relative">
                            <div class="relative inline-block kebab-menu-container">
                                <button 
                                    @click="openMenu(log, $event)"
                                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-700/50 rounded-lg transition-colors"
                                    :class="{'bg-surface-100 dark:bg-surface-700/50': activeMenuLog?.id === log.id}"
                                >
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-surface-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Empty State --}}
                <template x-if="!loading && logs.length === 0">
                    <tr>
                        <td colspan="9" class="px-4 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="activity" class="w-8 h-8 text-surface-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-1">Tidak Ada Data</h3>
                                <p class="text-sm text-surface-500 dark:text-surface-400">Belum ada log aktivitas yang tercatat atau sesuai filter.</p>
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
    /* Ensure dropdown is visible */
    tr:last-child .kebab-menu-container > div[x-show],
    tr:nth-last-child(2) .kebab-menu-container > div[x-show] {
        top: auto;
        bottom: 100%;
        margin-top: 0;
        margin-bottom: 0.25rem;
    }
</style>
