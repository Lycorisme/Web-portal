<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm">
    <h3 class="font-bold text-surface-900 dark:text-white mb-4">Aksi Cepat</h3>
     <div class="grid grid-cols-2 gap-3">
        
        <a href="<?php echo e(route('articles')); ?>" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="pen-tool" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Tulis Berita</span>
        </a>

        
        <a href="<?php echo e(route('galleries')); ?>" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="image" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Galeri</span>
        </a>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['is_admin'] ?? false): ?>
        
        <a href="<?php echo e(route('settings')); ?>" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Pengaturan</span>
        </a>

        
        <a href="<?php echo e(route('reports')); ?>" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
             <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Laporan</span>
        </a>

        <?php elseif($stats['is_editor'] ?? false): ?>
        
        <a href="<?php echo e(route('articles')); ?>?status=pending" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="file-check" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Review</span>
        </a>

        
        <a href="<?php echo e(route('categories')); ?>" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
             <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="folder" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Kategori</span>
        </a>

        <?php else: ?>
        
        <a href="<?php echo e(route('articles')); ?>?status=draft" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="file-edit" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Draft Saya</span>
        </a>

        
        <a href="<?php echo e(route('profile')); ?>" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
             <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Profil Saya</span>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard\partials\quick-actions.blade.php ENDPATH**/ ?>