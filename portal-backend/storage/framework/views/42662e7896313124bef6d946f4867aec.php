

<script>
// Command Palette Configuration Data
const commandPaletteConfig = {
    modes: [
        { id: 'navigate', label: 'Navigasi', icon: 'compass' },
        { id: 'create', label: 'Buat Baru', icon: 'plus-circle' },
        { id: 'commands', label: 'Perintah', icon: 'terminal' },
        { id: 'theme', label: 'Tema', icon: 'palette' },
    ],

    themes: [
        { id: 'indigo', label: 'Indigo', from: '#6366f1', to: '#4f46e5' },
        { id: 'emerald', label: 'Emerald', from: '#10b981', to: '#059669' },
        { id: 'rose', label: 'Rose', from: '#f43f5e', to: '#e11d48' },
        { id: 'amber', label: 'Amber', from: '#f59e0b', to: '#d97706' },
        { id: 'cyan', label: 'Cyan', from: '#06b6d4', to: '#0891b2' },
        { id: 'violet', label: 'Violet', from: '#8b5cf6', to: '#7c3aed' },
        { id: 'slate', label: 'Slate', from: '#64748b', to: '#475569' },
        { id: 'ocean', label: 'Ocean', from: '#3b82f6', to: '#0891b2' },
        { id: 'sunset', label: 'Sunset', from: '#f97316', to: '#e11d48' },
    ],

    quickActions: [
        { icon: 'layout-dashboard', label: 'Dashboard', description: 'Kembali ke dashboard utama', url: '<?php echo e(route("dashboard")); ?>', shortcut: 'D' },
        { icon: 'file-text', label: 'Artikel', description: 'Kelola semua artikel', url: '<?php echo e(route("articles")); ?>', shortcut: 'A' },
        { icon: 'image', label: 'Galeri', description: 'Kelola galeri media', url: '<?php echo e(route("galleries")); ?>', shortcut: 'G' },
        <?php if(Auth::user()?->canManageCategories()): ?>
        { icon: 'folder', label: 'Kategori', description: 'Kelola kategori artikel', url: '<?php echo e(route("categories")); ?>' },
        { icon: 'tag', label: 'Tag', description: 'Kelola tag artikel', url: '<?php echo e(route("tags")); ?>' },
        <?php endif; ?>
        <?php if(Auth::user()?->canManageUsers()): ?>
        { icon: 'users', label: 'Pengguna', description: 'Kelola pengguna sistem', url: '<?php echo e(route("users")); ?>', shortcut: 'U' },
        <?php endif; ?>
        <?php if(Auth::user()?->canAccessSettings()): ?>
        { icon: 'settings', label: 'Pengaturan', description: 'Konfigurasi sistem', url: '<?php echo e(route("settings")); ?>', shortcut: 'S' },
        <?php endif; ?>
        { icon: 'user', label: 'Profil Saya', description: 'Lihat dan edit profil', url: '<?php echo e(route("profile")); ?>', shortcut: 'P' },
        { icon: 'globe', label: 'Halaman Publik', description: 'Buka website publik', url: '<?php echo e(route("public.home")); ?>' },
    ],

    createActions: [
        { icon: 'file-plus', label: 'Artikel Baru', description: 'Buat artikel baru', url: '<?php echo e(route("articles")); ?>?action=create' },
        { icon: 'image-plus', label: 'Upload Galeri', description: 'Upload gambar ke galeri', url: '<?php echo e(route("galleries")); ?>?action=upload' },
        <?php if(Auth::user()?->canManageCategories()): ?>
        { icon: 'folder-plus', label: 'Kategori Baru', description: 'Buat kategori baru', url: '<?php echo e(route("categories")); ?>?action=create' },
        { icon: 'tag', label: 'Tag Baru', description: 'Buat tag baru', url: '<?php echo e(route("tags")); ?>?action=create' },
        <?php endif; ?>
        <?php if(Auth::user()?->canManageUsers()): ?>
        { icon: 'user-plus', label: 'Pengguna Baru', description: 'Tambah pengguna baru', url: '<?php echo e(route("users")); ?>?action=create' },
        <?php endif; ?>
    ],

    systemCommands: [

        { id: 'copy-url', icon: 'link', label: 'Salin URL', description: 'Salin URL halaman saat ini', shortcut: 'Ctrl+Shift+C', action: 'copyCurrentUrl' },
        { id: 'fullscreen', icon: 'maximize', label: 'Toggle Fullscreen', description: 'Masuk/keluar mode layar penuh', shortcut: 'F11', action: 'toggleFullscreen' },
        { id: 'refresh', icon: 'refresh-cw', label: 'Refresh Halaman', description: 'Muat ulang halaman', shortcut: 'Ctrl+R', action: 'refreshPage' },
        { id: 'print', icon: 'printer', label: 'Cetak Halaman', description: 'Cetak halaman saat ini', shortcut: 'Ctrl+P', action: 'printPage' },
        { id: 'clear-cache', icon: 'trash-2', label: 'Hapus Cache Lokal', description: 'Bersihkan data cache browser', action: 'clearLocalCache' },
        { id: 'logout', icon: 'log-out', label: 'Keluar', description: 'Logout dari sistem', action: 'logout' },
    ],

    searchUrl: '<?php echo e(route("global-search")); ?>',
};
</script>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\partials\command-palette\config.blade.php ENDPATH**/ ?>