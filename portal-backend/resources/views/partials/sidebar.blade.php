{{-- Sidebar Component --}}
<aside :class="sidebarOpen ? 'w-72' : 'w-20'"
    class="fixed left-0 top-0 h-full bg-white/80 dark:bg-surface-900/80 backdrop-blur-xl border-r border-surface-200/50 dark:border-surface-800/50 transition-all duration-300 ease-out z-50 flex flex-col shadow-xl shadow-surface-900/5"
    x-cloak>

    {{-- Logo Section --}}
    <div class="p-6 border-b border-surface-200/50 dark:border-surface-800/50">
        <div class="flex items-center gap-3">
            <div class="relative flex-shrink-0">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 via-primary-600 to-accent-violet flex items-center justify-center shadow-lg shadow-primary-500/30 transform hover:scale-105 transition-transform duration-300">
                    <span class="text-white font-space font-bold text-xl">B</span>
                </div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-accent-emerald rounded-full border-2 border-white dark:border-surface-900 animate-pulse"></div>
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0" class="overflow-hidden whitespace-nowrap">
                <h1 class="font-space font-bold text-lg text-surface-900 dark:text-white">BTIKP</h1>
                <p class="text-xs text-surface-500 dark:text-surface-400">Portal Admin</p>
            </div>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto overflow-x-hidden">
        {{-- Menu Utama --}}
        <p x-show="sidebarOpen"
            class="px-3 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider mb-4 mt-2 transition-opacity duration-300">
            Menu Utama</p>

        <a href="{{ route('dashboard') }}"
            :class="'{{ request()->routeIs('dashboard') }}' ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
            :title="!sidebarOpen ? 'Dashboard' : ''">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Dashboard</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'Kelola Berita' : ''">
            <i data-lucide="newspaper" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Kelola Berita</span>
            <span x-show="sidebarOpen"
                class="ml-auto bg-accent-cyan/20 text-accent-cyan text-xs font-semibold px-2 py-0.5 rounded-full">24</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'Galeri' : ''">
            <i data-lucide="image" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Galeri</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'Tong Sampah' : ''">
            <i data-lucide="trash-2" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Tong Sampah</span>
            <span x-show="sidebarOpen"
                class="ml-auto bg-accent-rose/20 text-accent-rose text-xs font-semibold px-2 py-0.5 rounded-full">3</span>
        </a>

        <div class="my-4 border-t border-surface-200/50 dark:border-surface-800/50 mx-2"></div>

        {{-- Keamanan --}}
        <p x-show="sidebarOpen"
            class="px-3 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider mb-4 transition-opacity duration-300">
            Keamanan</p>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'Activity Log' : ''">
            <i data-lucide="activity" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Activity Log</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'IP Terblokir' : ''">
            <i data-lucide="shield-ban" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">IP Terblokir</span>
            <span x-show="sidebarOpen"
                class="ml-auto bg-accent-amber/20 text-accent-amber text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
        </a>

        <div class="my-4 border-t border-surface-200/50 dark:border-surface-800/50 mx-2"></div>

        {{-- Pengaturan --}}
        <p x-show="sidebarOpen"
            class="px-3 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider mb-4 transition-opacity duration-300">
            Pengaturan</p>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'Pengaturan Situs' : ''">
            <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Pengaturan Situs</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50"
            :title="!sidebarOpen ? 'Kelola User' : ''">
            <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Kelola User</span>
        </a>
    </nav>

    {{-- Sidebar Toggle Button --}}
    <div class="p-4 border-t border-surface-200/50 dark:border-surface-800/50">
        <button @click="sidebarOpen = !sidebarOpen"
            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-surface-100 dark:bg-surface-800/50 hover:bg-surface-200 dark:hover:bg-surface-800 text-surface-600 dark:text-surface-400 transition-all duration-200">
            <i data-lucide="panel-left-close" x-show="sidebarOpen" class="w-5 h-5"></i>
            <i data-lucide="panel-left-open" x-show="!sidebarOpen" class="w-5 h-5"></i>
            <span x-show="sidebarOpen" class="font-medium text-sm whitespace-nowrap">Tutup Sidebar</span>
        </button>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak></div>
