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
        <table class="w-full min-w-[1100px]">
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
                        Pengguna
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap">
                        Login
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
                            'bg-cyan-50/50 dark:bg-cyan-900/10': isUnderReview(client),
                            'bg-blue-50/40 dark:bg-blue-900/10': isLoggedIn(client),
                            'bg-emerald-50/30 dark:bg-emerald-900/10': !client.is_blocked && !isUnderReview(client) && !isLoggedIn(client)
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
                                     :class="{
                                        'bg-gradient-to-br from-rose-400 to-rose-600': client.is_blocked,
                                        'bg-gradient-to-br from-cyan-400 to-cyan-600': isUnderReview(client),
                                        'bg-gradient-to-br from-emerald-400 to-emerald-600': !client.is_blocked && !isUnderReview(client)
                                     }">
                                    <i :data-lucide="client.is_blocked ? 'shield-ban' : (isUnderReview(client) ? 'eye' : 'shield-check')" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-mono font-semibold text-surface-900 dark:text-white block" x-text="client.ip_address"></span>
                                    <span class="text-xs text-surface-500 truncate max-w-[200px] block" x-text="client.user_agent ? client.user_agent.substring(0, 40) + '...' : '-'"></span>
                                </div>
                            </div>
                        </td>

                        {{-- User --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <template x-if="client.user_name">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar --}}
                                    <template x-if="client.user_avatar">
                                        <img 
                                            :src="client.user_avatar" 
                                            :alt="client.user_name"
                                            class="w-9 h-9 rounded-full object-cover border border-surface-200 dark:border-surface-700 shadow-sm"
                                        >
                                    </template>
                                    <template x-if="!client.user_avatar">
                                        <div class="w-9 h-9 rounded-full bg-theme-gradient flex items-center justify-center flex-shrink-0 shadow-sm border border-white/20">
                                            <span class="text-white text-xs font-bold" x-text="client.user_name.charAt(0).toUpperCase()"></span>
                                        </div>
                                    </template>

                                    {{-- Name & Time --}}
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-surface-900 dark:text-white" x-text="client.user_name"></span>
                                        <span class="text-xs text-surface-500" x-text="client.last_login_at_human || ''"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!client.user_name">
                                <span class="text-xs text-surface-400 italic">Tidak diketahui</span>
                            </template>
                        </td>



                        {{-- Login Count --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <template x-if="client.login_count > 0">
                                <span 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400"
                                >
                                    <i data-lucide="log-in" class="w-3 h-3"></i>
                                    <span x-text="client.login_count"></span>
                                </span>
                            </template>
                            <template x-if="!client.login_count || client.login_count === 0">
                                <span class="text-xs text-surface-400">-</span>
                            </template>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <div class="flex flex-col gap-1 items-center">
                                <span 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :class="{
                                        'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400': client.is_blocked && !isExpired(client),
                                        'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400': client.is_blocked && isExpired(client),
                                        'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-400': isUnderReview(client),
                                        'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400': isLoggedIn(client),
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400': !client.is_blocked && !isUnderReview(client) && !isLoggedIn(client)
                                    }"
                                >
                                    <i :data-lucide="getStatusIcon(client)" class="w-3 h-3"></i>
                                    <span x-text="getStatusLabel(client)"></span>
                                </span>
                                
                                <span 
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium"
                                    :class="client.attempt_count >= 5 
                                        ? 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                                        : client.attempt_count >= 3
                                            ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400'
                                            : 'bg-surface-100 text-surface-600 dark:bg-surface-800 dark:text-surface-400'"
                                    x-show="client.attempt_count > 0"
                                >
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                    <span x-text="client.attempt_count + ' Percobaan'"></span>
                                </span>
                            </div>
                        </td>

                        {{-- Blocked Until / Expired --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{-- IP Ditinjau (Under Review) --}}
                            <template x-if="isUnderReview(client)">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-cyan-100 dark:bg-cyan-500/20 rounded-lg">
                                        <i data-lucide="hourglass" class="w-3 h-3 text-cyan-600 dark:text-cyan-400"></i>
                                        <span class="text-xs font-medium text-cyan-700 dark:text-cyan-400">Menunggu Keputusan</span>
                                    </span>
                                </div>
                            </template>
                            {{-- IP Terblokir dengan waktu --}}
                            <template x-if="!isUnderReview(client) && client.is_blocked && client.blocked_until">
                                <div>
                                    <span class="text-sm text-surface-700 dark:text-surface-300" x-text="formatDate(client.blocked_until)"></span>
                                    <span 
                                        class="block text-xs" 
                                        :class="isExpired(client) ? 'text-amber-600' : 'text-surface-500'"
                                        x-text="isExpired(client) ? 'Expired' : getTimeRemaining(client.blocked_until)"
                                    ></span>
                                </div>
                            </template>
                            {{-- IP Terblokir Permanen --}}
                            <template x-if="!isUnderReview(client) && client.is_blocked && !client.blocked_until">
                                <span class="text-sm text-rose-600 dark:text-rose-400 font-medium">Permanen</span>
                            </template>
                            {{-- IP Tidak Terblokir (bukan ditinjau) --}}
                            <template x-if="!isUnderReview(client) && !client.is_blocked">
                                <span class="text-sm text-surface-400">-</span>
                            </template>
                        </td>

                        {{-- Reason --}}
                        <td class="px-4 py-3">
                            <span class="text-sm text-surface-700 dark:text-surface-300 line-clamp-2 max-w-[200px]" x-text="(client.reason && client.reason !== 'Login tercatat') ? client.reason : '-'"></span>
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
                        <td colspan="10" class="px-4 py-12">
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
