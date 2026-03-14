
<?php
    $siteName = \App\Models\SiteSetting::get('site_name', 'BTIKP');
    $siteTagline = \App\Models\SiteSetting::get('site_tagline', 'Portal Admin');
    $logoUrl = \App\Models\SiteSetting::get('logo_url', '');
?>


<aside 
    id="admin-sidebar-mobile"
    x-data="{ 
        trashedCount: <?php echo e($trashedCount ?? 0); ?>,
        blockedCount: 0,
        async fetchTrashCount() {
            try {
                const response = await fetch('<?php echo e(route('trash.count')); ?>');
                const data = await response.json();
                this.trashedCount = data.count || 0;
            } catch (e) {
                console.error('Failed to fetch trash count:', e);
            }
        },
        async fetchBlockedCount() {
            try {
                const response = await fetch('<?php echo e(route('blocked-clients.count')); ?>');
                const data = await response.json();
                this.blockedCount = data.count || 0;
            } catch (e) {
                console.error('Failed to fetch blocked count:', e);
            }
        }
    }"
    x-init="
        fetchTrashCount();
        fetchBlockedCount();
        document.addEventListener('livewire:navigated', () => { fetchTrashCount(); fetchBlockedCount(); });
        window.addEventListener('trash-updated', () => fetchTrashCount());
    "
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed left-0 top-0 w-72 h-screen bg-white/95 dark:bg-surface-900/95 backdrop-blur-xl border-r border-surface-200/50 dark:border-surface-800/50 transition-all duration-300 ease-out z-50 flex flex-col shadow-xl shadow-surface-900/5 overflow-hidden"
    x-cloak>

    
    <div class="p-4 lg:p-5 border-b border-surface-200/50 dark:border-surface-800/50">
        <div class="flex items-center" :class="sidebarOpen ? 'gap-3' : 'justify-center'">
            <div class="relative flex-shrink-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($logoUrl)): ?>
                    <div id="sidebar-logo" class="w-11 h-11 rounded-xl overflow-hidden shadow-theme transform hover:scale-105 transition-transform duration-300">
                        <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>" class="w-full h-full object-cover">
                    </div>
                <?php else: ?>
                    <div id="sidebar-logo" class="w-11 h-11 rounded-xl bg-theme-gradient flex items-center justify-center shadow-theme transform hover:scale-105 transition-transform duration-300">
                        <span id="sidebar-logo-initial" class="text-white font-space font-bold text-lg"><?php echo e(strtoupper(substr($siteName, 0, 1))); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-accent-emerald rounded-full border-2 border-white dark:border-surface-900"></div>
            </div>
            <div x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0" class="flex-1 min-w-0">
                <h1 id="sidebar-site-name" class="font-space font-bold text-base text-surface-900 dark:text-white truncate"><?php echo e($siteName); ?></h1>
                <p id="sidebar-site-tagline" class="text-xs text-surface-500 dark:text-surface-400 truncate"><?php echo e($siteTagline); ?></p>
            </div>
        </div>
    </div>

    
    <nav id="sidebar-nav" 
        x-on:scroll.debounce.50ms="sessionStorage.setItem('sidebarScroll', $el.scrollTop)"
        x-init="$nextTick(() => { $el.scrollTop = sessionStorage.getItem('sidebarScroll') || 0 })"
        class="flex-1 p-3 space-y-1.5 overflow-y-auto overflow-x-hidden">
        
        <p x-show="sidebarOpen" x-cloak
            class="px-3 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider mb-3 mt-2 transition-opacity duration-300">
            Menu Utama</p>

        
        <a href="<?php echo e(route('dashboard')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group relative overflow-hidden <?php echo e(request()->routeIs('dashboard') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Dashboard' : ''">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Dashboard</span>
        </a>

        
        <a href="<?php echo e(route('articles')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('articles*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Kelola Berita' : ''">
            <i data-lucide="newspaper" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Kelola Berita</span>
        </a>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->canManageCategories()): ?>
        <a href="<?php echo e(route('categories')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('categories*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Kategori' : ''">
            <i data-lucide="folder-tree" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Kategori</span>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(auth()->user()->canManageTags()): ?>
        <a href="<?php echo e(route('tags')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('tags*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Tag' : ''">
            <i data-lucide="tags" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Tag</span>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <a href="<?php echo e(route('galleries')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('galleries*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Galeri' : ''">
            <i data-lucide="image" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Galeri</span>
        </a>

        
        <?php if(auth()->user()->canAccessSecurity()): ?>
        <div class="my-3 border-t border-surface-200/50 dark:border-surface-800/50 mx-2"></div>
        
        <p x-show="sidebarOpen" x-cloak
            class="px-3 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider mb-3 transition-opacity duration-300">
            Keamanan</p>

        
        <a href="<?php echo e(route('activity-log')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('activity-log*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Activity Log' : ''">
            <i data-lucide="shield-check" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Activity Log</span>
        </a>

        
        <a href="<?php echo e(route('blocked-clients')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('blocked-clients*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Blokir IPs' : ''">
            <i data-lucide="shield-ban" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Blokir IPs</span>
            <span x-show="sidebarOpen && blockedCount > 0" x-cloak
                class="ml-auto bg-accent-amber/20 text-accent-amber text-xs font-semibold px-2 py-0.5 rounded-full"
                x-text="blockedCount"></span>
        </a>

        
        <a href="<?php echo e(route('reports')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('reports*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Laporan' : ''">
            <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Laporan</span>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="my-3 border-t border-surface-200/50 dark:border-surface-800/50 mx-2"></div>

        <p x-show="sidebarOpen" x-cloak
            class="px-3 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider mb-3 transition-opacity duration-300">
            Pengaturan</p>

        
        <?php if(auth()->user()->canAccessSettings()): ?>
        <a href="<?php echo e(route('settings')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('settings') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Pengaturan Situs' : ''">
            <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Pengaturan Situs</span>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(auth()->user()->canManageUsers()): ?>
        <a href="<?php echo e(route('users')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('users*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Management User' : ''">
            <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Management User</span>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <a href="<?php echo e(route('trash')); ?>" wire:navigate
            class="flex items-center rounded-xl transition-all duration-200 group <?php echo e(request()->routeIs('trash*') ? 'bg-theme-gradient text-white shadow-theme' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800/50'); ?>"
            :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center p-3'"
            :title="!sidebarOpen ? 'Tong Sampah' : ''">
            <i data-lucide="trash-2" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-cloak class="font-medium whitespace-nowrap">Tong Sampah</span>
            <span x-show="sidebarOpen && trashedCount > 0" x-cloak
                class="ml-auto bg-accent-rose/20 text-accent-rose text-xs font-semibold px-2 py-0.5 rounded-full"
                x-text="trashedCount"></span>
        </a>
    </nav>

    
    <div x-show="sidebarOpen" x-cloak class="mt-auto flex-shrink-0 p-4 border-t border-surface-200/50 dark:border-surface-800/50">
        <div class="flex items-center gap-3 px-3 py-2 bg-surface-100 dark:bg-surface-800/50 rounded-xl">
            <div class="w-8 h-8 rounded-lg bg-theme-gradient flex items-center justify-center flex-shrink-0">
                <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p id="sidebar-footer-name" class="text-xs font-medium text-surface-700 dark:text-surface-300 truncate"><?php echo e($siteName); ?></p>
                <p class="text-xs text-surface-400">v1.0.0</p>
            </div>
        </div>
    </div>
</aside><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\partials\sidebar-mobile.blade.php ENDPATH**/ ?>