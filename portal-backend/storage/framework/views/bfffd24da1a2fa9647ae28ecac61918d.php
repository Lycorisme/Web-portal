
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-amber to-orange-500 flex items-center justify-center shadow-lg shadow-accent-amber/30 flex-shrink-0">
                <i data-lucide="activity" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Aktivitas Terbaru</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Riwayat aktivitas akun Anda</p>
            </div>
        </div>
        <a href="<?php echo e(route('activity-log')); ?>" 
           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
            <span>Lihat Semua</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentActivities->count() > 0): ?>
    <div class="space-y-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-start gap-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors duration-200">
            
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                <?php switch($activity->action):
                    case ('create'): ?>
                        bg-accent-emerald/20
                        <?php break; ?>
                    <?php case ('update'): ?>
                        bg-accent-cyan/20
                        <?php break; ?>
                    <?php case ('delete'): ?>
                        bg-accent-rose/20
                        <?php break; ?>
                    <?php case ('login'): ?>
                        bg-primary-100 dark:bg-primary-900/30
                        <?php break; ?>
                    <?php default: ?>
                        bg-surface-200 dark:bg-surface-700
                <?php endswitch; ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($activity->action):
                    case ('create'): ?>
                        <i data-lucide="plus-circle" class="w-5 h-5 text-accent-emerald"></i>
                        <?php break; ?>
                    <?php case ('update'): ?>
                        <i data-lucide="edit-3" class="w-5 h-5 text-accent-cyan"></i>
                        <?php break; ?>
                    <?php case ('delete'): ?>
                        <i data-lucide="trash-2" class="w-5 h-5 text-accent-rose"></i>
                        <?php break; ?>
                    <?php case ('login'): ?>
                        <i data-lucide="log-in" class="w-5 h-5 text-primary-600 dark:text-primary-400"></i>
                        <?php break; ?>
                    <?php default: ?>
                        <i data-lucide="activity" class="w-5 h-5 text-surface-500"></i>
                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-surface-900 dark:text-white truncate">
                    <?php echo e($activity->description ?? ucfirst($activity->action) . ' ' . class_basename($activity->model_type)); ?>

                </p>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-surface-500 dark:text-surface-400">
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <?php echo e($activity->created_at->diffForHumans()); ?>

                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->ip_address): ?>
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="globe" class="w-3 h-3"></i>
                        <?php echo e($activity->ip_address); ?>

                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                    <?php switch($activity->action):
                        case ('create'): ?>
                            bg-accent-emerald/20 text-accent-emerald
                            <?php break; ?>
                        <?php case ('update'): ?>
                            bg-accent-cyan/20 text-accent-cyan
                            <?php break; ?>
                        <?php case ('delete'): ?>
                            bg-accent-rose/20 text-accent-rose
                            <?php break; ?>
                        <?php case ('login'): ?>
                            bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400
                            <?php break; ?>
                        <?php default: ?>
                            bg-surface-200 dark:bg-surface-700 text-surface-600 dark:text-surface-400
                    <?php endswitch; ?>">
                    <?php echo e(ucfirst($activity->action)); ?>

                </span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-8">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
            <i data-lucide="inbox" class="w-8 h-8 text-surface-400"></i>
        </div>
        <p class="text-surface-500 dark:text-surface-400">Belum ada aktivitas tercatat</p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\profile\partials\activity-card.blade.php ENDPATH**/ ?>