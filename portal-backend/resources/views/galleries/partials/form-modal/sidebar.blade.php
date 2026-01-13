{{-- Sidebar Navigation --}}
<div class="w-16 sm:w-20 md:w-72 flex-shrink-0 bg-surface-50/50 dark:bg-surface-900/50 border-r border-surface-200/50 dark:border-surface-700/50 overflow-y-auto backdrop-blur-sm p-2 sm:p-4 md:p-6 flex flex-col justify-between pb-10">
    <nav class="space-y-2">
        <template x-for="item in [
            { id: 'basic', icon: 'file-text', label: 'Informasi Dasar', desc: 'Judul & Detail' },
            { id: 'media', icon: 'image', label: 'Media & Visual', desc: 'Foto & Video' },
            { id: 'settings', icon: 'sliders-horizontal', label: 'Pengaturan', desc: 'Status & Opsi' }
        ]">
            <button type="button" @click="activeTab = item.id"
                class="w-full flex items-center gap-0 md:gap-4 px-0 md:px-4 py-3 rounded-xl transition-all group relative justify-center md:justify-start">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-theme-500 rounded-l-full transition-all"
                    :class="activeTab === item.id ? 'opacity-100' : 'opacity-0'"></div>
                <div class="p-2.5 rounded-xl" :class="activeTab === item.id ? 'text-theme-600 dark:text-theme-400' : 'text-surface-400'">
                    <i :data-lucide="item.icon" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-bold" :class="activeTab === item.id ? 'text-theme-600 dark:text-theme-400' : 'text-surface-600'" x-text="item.label"></p>
                    <p class="text-[10px] text-surface-400" x-text="item.desc"></p>
                </div>
            </button>
        </template>
    </nav>
</div>
