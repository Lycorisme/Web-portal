{{-- Sidebar Navigation --}}
<div class="w-16 sm:w-20 md:w-72 flex-shrink-0 bg-surface-50/50 dark:bg-surface-900/50 border-r border-surface-200/50 dark:border-surface-700/50 overflow-y-auto backdrop-blur-sm p-2 sm:p-4 md:p-6 flex flex-col justify-between pb-10">
    <nav class="space-y-2">
        <template x-for="item in [
            { id: 'content', icon: 'file-text', label: 'Konten Utama', desc: 'Judul & Artikel' },
            { id: 'media', icon: 'image', label: 'Media & Visual', desc: 'Thumbnail' },
            { id: 'seo', icon: 'globe', label: 'SEO & Social', desc: 'Meta Tags' },
            { id: 'settings', icon: 'sliders-horizontal', label: 'Pengaturan', desc: 'Status' }
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
    <template x-if="formMode === 'edit' && auditInfo">
        <div class="hidden md:block mt-6 pt-6 border-t border-surface-200 dark:border-surface-700/50 space-y-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-surface-800/50 border border-surface-100 dark:border-surface-800">
                <div class="h-8 w-8 rounded-full bg-theme-100 dark:bg-theme-900/30 flex items-center justify-center overflow-hidden">
                    <template x-if="auditInfo.created_by_avatar">
                        <img :src="auditInfo.created_by_avatar" class="w-full h-full object-cover" :alt="auditInfo.created_by">
                    </template>
                    <template x-if="!auditInfo.created_by_avatar">
                        <i data-lucide="user" class="w-4 h-4 text-theme-600"></i>
                    </template>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-surface-400">Dibuat oleh</p>
                    <p class="text-xs font-semibold text-surface-900 dark:text-white truncate" x-text="auditInfo.created_by"></p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-3 py-2 text-xs">
                <i data-lucide="calendar" class="w-3 h-3 text-surface-400"></i>
                <span class="text-surface-400">Dibuat:</span>
                <span class="text-surface-600 dark:text-surface-300" x-text="auditInfo.created_at || '-'"></span>
            </div>
        </div>
    </template>
</div>
