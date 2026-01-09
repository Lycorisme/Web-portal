{{-- Global Floating Action Menu --}}
<template x-teleport="#main-content">
    <div 
        x-show="activeMenuUser" 
        x-cloak
        @click.away="closeMenu()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="fixed z-20 w-48 bg-white dark:bg-surface-800 rounded-xl shadow-xl border border-surface-100 dark:border-surface-700 py-1.5 overflow-hidden ring-1 ring-black/5 transition-[top,left] duration-75 ease-out"
        :class="menuPosition.placement === 'top' ? 'origin-bottom-right' : 'origin-top-right'"
        :style="`top: ${menuPosition.top}px; left: ${menuPosition.left}px; transform: ${menuPosition.placement === 'top' ? 'translateY(-100%)' : ''}`"
    >
        {{-- Common Actions (Only for Active Items) --}}
        <button 
            x-show="activeMenuUser && !activeMenuUser.deleted_at"
            @click="viewDetail(activeMenuUser.id); closeMenu()"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50 hover:text-theme-600 dark:hover:text-theme-400 transition-colors group"
        >
            <i data-lucide="eye" class="w-4 h-4 text-surface-400 group-hover:text-theme-500 transition-colors"></i>
            <span>Lihat Detail</span>
        </button>

        <button 
            x-show="activeMenuUser && !activeMenuUser.deleted_at"
            @click="openEditModal(activeMenuUser)"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50 hover:text-blue-600 dark:hover:text-blue-400 transition-colors group"
        >
            <i data-lucide="pencil" class="w-4 h-4 text-surface-400 group-hover:text-blue-500 transition-colors"></i>
            <span>Edit</span>
        </button>

        {{-- Unlock Button (Only for Locked Users) --}}
        <button 
            x-show="activeMenuUser && !activeMenuUser.deleted_at && activeMenuUser.is_locked"
            @click="unlockUser(activeMenuUser.id); closeMenu()"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors group"
        >
            <i data-lucide="unlock" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
            <span>Buka Kunci</span>
        </button>

        {{-- Actions for Active Items --}}
        <div x-show="activeMenuUser && !activeMenuUser.deleted_at && activeMenuUser.id !== currentUserId && (currentUserIsSuperAdmin || activeMenuUser.role !== 'super_admin')">
            <div class="h-px bg-surface-100 dark:bg-surface-700/50 my-1 mx-2"></div>
            <button 
                @click="deleteUser(activeMenuUser.id); closeMenu()"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors group"
            >
                <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                <span>Hapus</span>
            </button>
        </div>

        {{-- Self Warning --}}
        <div x-show="activeMenuUser && !activeMenuUser.deleted_at && activeMenuUser.id === currentUserId">
            <div class="h-px bg-surface-100 dark:bg-surface-700/50 my-1 mx-2"></div>
            <div class="px-4 py-2.5 text-xs text-surface-500 italic">
                Anda tidak dapat menghapus akun sendiri
            </div>
        </div>

        {{-- Actions for Trash Items --}}
        <div x-show="activeMenuUser && activeMenuUser.deleted_at">
            <div class="h-px bg-surface-100 dark:bg-surface-700/50 my-1 mx-2"></div>
            <button 
                @click="restoreUser(activeMenuUser.id); closeMenu()"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors group"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4 group-hover:-rotate-180 transition-transform"></i>
                <span>Pulihkan</span>
            </button>
            <button 
                @click="forceDeleteUser(activeMenuUser.id); closeMenu()"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors group"
            >
                <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                <span>Hapus Permanen</span>
            </button>
        </div>
    </div>
</template>
