<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm flex-1">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-surface-900 dark:text-white">Aktivitas</h3>
         <a href="<?php echo e(route('activity-log')); ?>" wire:navigate class="p-1 hover:bg-surface-100 dark:hover:bg-surface-800 rounded transition-colors" title="Lihat Semua">
            <i data-lucide="history" class="w-4 h-4 text-surface-400"></i>
         </a>
    </div>
    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div 
            class="flex gap-3 relative pl-2 cursor-pointer hover:bg-surface-50 dark:hover:bg-surface-800/30 rounded-lg p-2 -mx-2 transition-colors"
            @click="openActivityModal(<?php echo e($log->id); ?>)"
        >
            <div class="absolute left-0 top-3.5 w-1.5 h-1.5 rounded-full <?php echo e($loop->first ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600'); ?>"></div>
            <div class="flex-1">
                <p class="text-xs text-surface-500 dark:text-surface-400 mb-0.5"><?php echo e($log->created_at->diffForHumans()); ?></p>
                <p class="text-sm text-surface-800 dark:text-surface-200 leading-snug">
                    <span class="font-semibold"><?php echo e($log->user->name ?? 'System'); ?></span>
                    <?php echo e($log->description); ?>

                </p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-sm text-surface-400 py-4">Tidak ada aktivitas baru.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard\partials\activity-log.blade.php ENDPATH**/ ?>