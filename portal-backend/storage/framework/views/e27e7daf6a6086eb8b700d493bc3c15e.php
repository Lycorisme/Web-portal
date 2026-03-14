<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-bold text-surface-900 dark:text-white">Statistik Pengunjung</h3>
            <p class="text-xs text-surface-500 dark:text-surface-400">7 hari terakhir</p>
        </div>
        <div class="p-2 bg-surface-50 dark:bg-surface-800 rounded-lg">
            <i data-lucide="bar-chart-2" class="w-4 h-4 text-surface-400"></i>
        </div>
    </div>
    
    <?php
        $maxViews = max(array_column($visitStats, 'views')) ?: 1;
        // Calculate nice Y-axis max (round up to nearest 5 or 10)
        if ($maxViews <= 5) {
            $yMax = 5;
        } elseif ($maxViews <= 10) {
            $yMax = 10;
        } elseif ($maxViews <= 20) {
            $yMax = 20;
        } else {
            $yMax = ceil($maxViews / 10) * 10;
        }
        $ySteps = [0, round($yMax * 0.25), round($yMax * 0.5), round($yMax * 0.75), $yMax];
    ?>
    
    <div class="flex">
        
        <div class="flex flex-col justify-between text-right pr-3" style="height: 180px; width: 30px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_reverse($ySteps); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="text-[10px] text-surface-400 leading-none"><?php echo e($step); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        
        <div class="flex-1 relative border-l border-b border-surface-300 dark:border-surface-600" style="height: 180px; overflow: hidden;">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i < 5; $i++): ?>
                <div class="absolute w-full border-t border-surface-200 dark:border-surface-700" style="bottom: <?php echo e($i * 25); ?>%;"></div>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 100%; display: flex; align-items: flex-end; justify-content: space-around; padding: 0 8px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $visitStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $views = $stat['views'] ?? 0;
                    $chartHeight = 160; // Slightly less than container to leave space for labels
                    // Calculate pixel height based on yMax
                    $heightPx = $yMax > 0 ? round(($views / $yMax) * $chartHeight) : 0;
                    // Minimum height for visibility when there's data
                    if ($views > 0 && $heightPx < 8) {
                        $heightPx = 8;
                    }
                    // For zero, show tiny bar
                    if ($views == 0) {
                        $heightPx = 3;
                    }
                ?>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; width: 12%; height: 100%;">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($views > 0): ?>
                    <span style="font-size: 10px; font-weight: 600; color: #3b82f6; margin-bottom: 2px;"><?php echo e($views); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div style="width: 100%; height: <?php echo e($heightPx); ?>px; background-color: #3b82f6; border-radius: 3px 3px 0 0; flex-shrink: 0;"></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    
    
    <div class="flex" style="padding-left: 30px; margin-top: 8px;">
        <div class="flex-1 flex justify-around">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $visitStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="text-[10px] text-surface-500 dark:text-surface-400 text-center" style="width: 12%;"><?php echo e($stat['day']); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard\partials\visitor-chart.blade.php ENDPATH**/ ?>