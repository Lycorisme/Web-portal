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
        <table class="w-full min-w-[900px]">
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
                        IP Address
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Route
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Percobaan
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Expired
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Alasan
                    </th>
                    <th class="w-16 px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-100 dark:divide-surface-800">
                <template x-for="client in clients" :key="client.id">
                    <tr 
                        class="transition-colors border-b border-surface-100 dark:border-surface-800 hover:bg-surface-50 dark:hover:bg-surface-800/30"
                        :class="{
                            'bg-rose-50/70 dark:bg-rose-900/10': client.is_blocked && !isExpired(client),
                            'bg-amber-50/50 dark:bg-amber-900/10': client.is_blocked && isExpired(client),
                            'bg-emerald-50/30 dark:bg-emerald-900/10': !client.is_blocked
                        }"
                    >
                        {{-- Checkbox --}}
                        <td class="px-4 py-3">
                            <input 
                                type="checkbox"
                                :value="client.id"
                                x-model="selectedIds"
                                class="w-4 h-4 rounded border-surface-300 dark:border-surface-600 text-theme-600 focus:ring-theme-500"
                            >
                        </td>

                        {{-- IP Address --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                     :class="client.is_blocked ? 'bg-gradient-to-br from-rose-400 to-rose-600' : 'bg-gradient-to-br from-emerald-400 to-emerald-600'">
                                    <i :data-lucide="client.is_blocked ? 'shield-ban' : 'shield-check'" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-mono font-semibold text-surface-900 dark:text-white block" x-text="client.ip_address"></span>
                                    <span class="text-xs text-surface-500 truncate max-w-[200px] block" x-text="client.user_agent ? client.user_agent.substring(0, 40) + '...' : '-'"></span>
                                </div>
                            </div>
                        </td>

                        {{-- Blocked Route --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm text-surface-700 dark:text-surface-300 font-mono" x-text="client.blocked_route || '-'"></span>
                        </td>

                        {{-- Attempt Count --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span 
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold"
                                :class="client.attempt_count >= 5 
                                    ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400'
                                    : client.attempt_count >= 3
                                        ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400'
                                        : 'bg-surface-100 text-surface-700 dark:bg-surface-700 dark:text-surface-300'"
                            >
                                <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                <span x-text="client.attempt_count"></span>
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span 
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                :class="{
                                    'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400': client.is_blocked && !isExpired(client),
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400': client.is_blocked && isExpired(client),
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400': !client.is_blocked
                                }"
                            >
                                <i :data-lucide="client.is_blocked ? (isExpired(client) ? 'clock' : 'shield-ban') : 'shield-check'" class="w-3 h-3"></i>
                                <span x-text="getStatusLabel(client)"></span>
                            </span>
                        </td>

                        {{-- Blocked Until --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <template x-if="client.blocked_until">
                                <div>
                                    <span class="text-sm text-surface-700 dark:text-surface-300" x-text="formatDate(client.blocked_until)"></span>
                                    <span 
                                        class="block text-xs" 
                                        :class="isExpired(client) ? 'text-amber-600' : 'text-surface-500'"
                                        x-text="isExpired(client) ? 'Expired' : getTimeRemaining(client.blocked_until)"
                                    ></span>
                                </div>
                            </template>
                            <template x-if="!client.blocked_until">
                                <span class="text-sm text-rose-600 dark:text-rose-400 font-medium">Permanen</span>
                            </template>
                        </td>

                        {{-- Reason --}}
                        <td class="px-4 py-3">
                            <span class="text-sm text-surface-700 dark:text-surface-300 line-clamp-2 max-w-[200px]" x-text="client.reason || '-'"></span>
                        </td>

                        {{-- Actions (Kebab Menu) --}}
                        <td class="px-4 py-3 text-center relative">
                            <div class="relative inline-block kebab-menu-container">
                                <button 
                                    @click="openMenu(client, $event)"
                                    class="p-2 hover:bg-surface-100 dark:hover:bg-surface-700/50 rounded-lg transition-colors"
                                    :class="{'bg-surface-100 dark:bg-surface-700/50': activeMenuClient?.id === client.id}"
                                >
                                    <i data-lucide="more-vertical" class="w-4 h-4 text-surface-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Empty State --}}
                <template x-if="!loading && clients.length === 0">
                    <tr>
                        <td colspan="8" class="px-4 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="shield-check" class="w-8 h-8 text-emerald-500"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-1">Tidak Ada Data</h3>
                                <p class="text-sm text-surface-500 dark:text-surface-400 mb-4">Belum ada IP yang terblokir.</p>
                                <button 
                                    @click="openCreateModal()"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-theme-gradient text-white font-medium text-sm rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20"
                                >
                                    <i data-lucide="shield-plus" class="w-4 h-4"></i>
                                    <span>Blokir IP Pertama</span>
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
