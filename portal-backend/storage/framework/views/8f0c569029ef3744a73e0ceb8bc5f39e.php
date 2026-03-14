<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
    
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-primary-500/50 dark:hover:border-primary-500/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-primary-500/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="newspaper" class="w-16 h-16 text-primary-500 transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">
                <?php echo e($stats['is_author'] ? 'Berita Saya' : 'Total Berita'); ?>

            </p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white"><?php echo e(number_format($stats['total_articles'])); ?></h3>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold <?php echo e($stats['article_growth'] >= 0 ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10' : 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10'); ?> px-2 py-1 rounded-full mb-1">
                    <i data-lucide="<?php echo e($stats['article_growth'] >= 0 ? 'trending-up' : 'trending-down'); ?>" class="w-3 h-3"></i>
                    <?php echo e(abs($stats['article_growth'])); ?>%
                </span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 rounded-full" style="width: <?php echo e($stats['total_articles'] > 0 ? ($stats['published_articles'] / $stats['total_articles']) * 100 : 0); ?>%;"></div>
            </div>
            <span><?php echo e($stats['published_articles']); ?> Pub</span>
        </div>
    </div>

    
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-cyan/50 dark:hover:border-accent-cyan/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-cyan/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="eye" class="w-16 h-16 text-accent-cyan transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">
                <?php echo e($stats['is_author'] ? 'Views Berita Saya' : 'Total Views'); ?>

            </p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white"><?php echo e($stats['total_views']); ?></h3>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold <?php echo e($stats['views_growth'] >= 0 ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10' : 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10'); ?> px-2 py-1 rounded-full mb-1">
                    <i data-lucide="<?php echo e($stats['views_growth'] >= 0 ? 'trending-up' : 'trending-down'); ?>" class="w-3 h-3"></i>
                    <?php echo e(abs($stats['views_growth'])); ?>%
                </span>
            </div>
        </div>
         <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <div class="h-full bg-accent-cyan rounded-full" style="width: 75%"></div>
            </div>
            <span><?php echo e(date('M Y')); ?></span>
        </div>
    </div>

    
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-amber/50 dark:hover:border-accent-amber/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-amber/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="<?php echo e($stats['is_author'] ? 'clock' : 'inbox'); ?>" class="w-16 h-16 text-accent-amber transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['is_author']): ?>
                    Menunggu Persetujuan
                <?php else: ?>
                    Antrean Review
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white"><?php echo e($stats['pending_articles']); ?></h3>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['pending_articles'] > 0): ?>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                    <?php echo e($stats['is_author'] ? 'Pending' : 'Perlu Aksi'); ?>

                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    <?php echo e($stats['is_author'] ? 'Clear' : 'Kosong'); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <?php 
                    $pendingPercentage = $stats['total_articles'] > 0 ? ($stats['pending_articles'] / $stats['total_articles']) * 100 : 0;
                ?>
                <div class="h-full bg-accent-amber rounded-full" style="width: <?php echo e($pendingPercentage); ?>%;"></div>
            </div>
            <span><?php echo e($stats['is_author'] ? 'Progres' : 'Queue'); ?></span>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['is_admin']): ?>
    
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-emerald-500/50 dark:hover:border-emerald-500/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-emerald-500/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="shield-check" class="w-16 h-16 text-emerald-500 transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Skor Keamanan</p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white"><?php echo e($securityScore ?? 100); ?>%</h3>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['blocked_ips'] > 0): ?>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded-full mb-1">
                    <?php echo e($stats['blocked_ips']); ?> BLK
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                    Aman
                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: <?php echo e($securityScore ?? 100); ?>%;"></div>
            </div>
            <span>Good</span>
        </div>
    </div>
    <?php elseif($stats['is_editor']): ?>
    
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-violet/50 dark:hover:border-accent-violet/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-violet/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="users" class="w-16 h-16 text-accent-violet transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Tim Aktif</p>
            <div class="flex items-end justify-between gap-2">
                 <h3 class="text-3xl font-bold text-surface-900 dark:text-white"><?php echo e($stats['active_admins']); ?></h3>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                    Online
                </span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <div class="h-full bg-accent-violet rounded-full" style="width: <?php echo e($stats['total_admins'] > 0 ? ($stats['active_admins'] / $stats['total_admins']) * 100 : 0); ?>%;"></div>
            </div>
            <span><?php echo e($stats['total_admins']); ?> Total</span>
        </div>
    </div>
    <?php else: ?>
    
    <div class="group bg-white dark:bg-surface-900 border border-surface-200 dark:border-surface-800 rounded-2xl p-5 hover:border-accent-violet/50 dark:hover:border-accent-violet/50 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-accent-violet/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i data-lucide="file-edit" class="w-16 h-16 text-accent-violet transform translate-x-4 -translate-y-4"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium text-surface-500 dark:text-surface-400 mb-1">Draft Saya</p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-surface-900 dark:text-white"><?php echo e($stats['draft_articles']); ?></h3>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['draft_articles'] > 0): ?>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="edit-3" class="w-3 h-3"></i>
                    Lanjutkan
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-x-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-full mb-1">
                    <i data-lucide="check" class="w-3 h-3"></i>
                    Kosong
                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-surface-400">
            <div class="flex-1 h-1.5 bg-surface-100 dark:bg-surface-800 rounded-full overflow-hidden">
                <?php 
                    $draftPercentage = $stats['total_articles'] > 0 ? ($stats['draft_articles'] / $stats['total_articles']) * 100 : 0;
                ?>
                <div class="h-full bg-accent-violet rounded-full" style="width: <?php echo e($draftPercentage); ?>%;"></div>
            </div>
            <span>Perlu Diselesaikan</span>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard\partials\stats-grid.blade.php ENDPATH**/ ?>