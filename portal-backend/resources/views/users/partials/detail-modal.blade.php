{{-- Detail Modal --}}
<template x-teleport="body">
    <div 
        x-show="showDetailModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showDetailModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="closeDetailModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.stop
            >
                {{-- Header with User Avatar --}}
                <template x-if="selectedUser">
                    <div>
                        <div class="bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg overflow-hidden bg-white/20 backdrop-blur-sm">
                                        <template x-if="selectedUser.profile_photo">
                                            <img :src="selectedUser.profile_photo" :alt="selectedUser.name" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!selectedUser.profile_photo">
                                            <span class="text-white font-bold text-xl" x-text="selectedUser.name.charAt(0).toUpperCase()"></span>
                                        </template>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white" x-text="selectedUser.name"></h3>
                                        <p class="text-sm text-white/80" x-text="selectedUser.email"></p>
                                    </div>
                                </div>
                                <button @click="closeDetailModal()" class="p-2 rounded-xl hover:bg-white/20 transition-colors">
                                    <i data-lucide="x" class="w-5 h-5 text-white"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 space-y-5">
                            {{-- Status Badges --}}
                            <div class="flex flex-wrap items-center gap-3">
                                {{-- Role Badge --}}
                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                    :class="{
                                        'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400': selectedUser.role === 'super_admin',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400': selectedUser.role === 'admin',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400': selectedUser.role === 'author'
                                    }"
                                >
                                    <i :data-lucide="selectedUser.role === 'super_admin' ? 'shield' : (selectedUser.role === 'admin' ? 'user-cog' : 'pen-tool')" class="w-4 h-4"></i>
                                    <span x-text="selectedUser.role_label"></span>
                                </span>

                                {{-- Lock Status Badge --}}
                                <span 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                    :class="selectedUser.is_locked 
                                        ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' 
                                        : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'"
                                >
                                    <i :data-lucide="selectedUser.is_locked ? 'lock' : 'check-circle'" class="w-4 h-4"></i>
                                    <span x-text="selectedUser.is_locked ? 'Terkunci' : 'Aktif'"></span>
                                </span>

                                {{-- Articles Count Badge --}}
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-surface-100 text-surface-700 dark:bg-surface-700 dark:text-surface-300">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    <span x-text="selectedUser.articles_count + ' Artikel'"></span>
                                </span>
                            </div>

                            {{-- User Info Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Position --}}
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Jabatan</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.position || '-'"></p>
                                </div>

                                {{-- Location --}}
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Lokasi</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.location || '-'"></p>
                                </div>

                                {{-- Phone --}}
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Telepon</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.phone || '-'"></p>
                                </div>

                                {{-- Failed Login --}}
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Login Gagal</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.failed_login_count + ' kali'"></p>
                                </div>
                            </div>

                            {{-- Bio --}}
                            <template x-if="selectedUser.bio">
                                <div>
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Bio</label>
                                    <p class="text-sm text-surface-700 dark:text-surface-300 leading-relaxed" x-text="selectedUser.bio"></p>
                                </div>
                            </template>

                            {{-- Login Info --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Login Terakhir</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.last_login_at || 'Belum pernah'"></p>
                                    <template x-if="selectedUser.last_login_ip">
                                        <p class="text-xs text-surface-500 mt-1" x-text="'IP: ' + selectedUser.last_login_ip"></p>
                                    </template>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl" x-show="selectedUser.is_locked && selectedUser.locked_until">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Terkunci Hingga</label>
                                    <p class="text-sm font-medium text-rose-600 dark:text-rose-400" x-text="selectedUser.locked_until"></p>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Dibuat</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.created_at"></p>
                                </div>
                                <div class="p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                                    <label class="block text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-1">Terakhir Update</label>
                                    <p class="text-sm font-medium text-surface-900 dark:text-white" x-text="selectedUser.updated_at"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center gap-3">
                            <button 
                                @click="closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"
                            >
                                Tutup
                            </button>
                            <button 
                                @click="openEditModal(selectedUser); closeDetailModal()"
                                class="flex-1 px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 flex items-center justify-center gap-2"
                            >
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                <span>Edit</span>
                            </button>
                        </div>


                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
