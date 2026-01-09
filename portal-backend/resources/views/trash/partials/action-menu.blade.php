{{-- Global Floating Action Menu --}}
<template x-teleport="#main-content">
    <div 
        x-show="activeMenuItem" 
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
        {{-- Restore Action --}}
        <button 
            @click="restoreItem(activeMenuItem); closeMenu()"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors group"
        >
            <i data-lucide="rotate-ccw" class="w-4 h-4 group-hover:-rotate-180 transition-transform"></i>
            <span>Pulihkan</span>
        </button>

        <div class="h-px bg-surface-100 dark:bg-surface-700/50 my-1 mx-2"></div>

        {{-- Force Delete Action --}}
        <button 
            x-show="activeMenuItem.type !== 'user' || activeMenuItem.role_code !== 'super_admin' || currentUserIsSuperAdmin"
            @click="forceDeleteItem(activeMenuItem); closeMenu()"
            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors group"
        >
            <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
            <span>Hapus Permanen</span>
        </button>
    </div>
</template>
