<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm flex flex-col">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h3 class="font-bold text-surface-900 dark:text-white">Distribusi</h3>
            <p class="text-xs text-surface-500 dark:text-surface-400">Kategori Berita</p>
        </div>
        <a href="#" class="text-xs text-primary-600 hover:underline">Detail</a>
    </div>
    
    <div class="flex-1 flex items-center gap-6">
        
        <div class="relative w-32 h-32 flex-shrink-0">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" class="stroke-surface-100 dark:stroke-surface-800" stroke-width="12"></circle>
                <?php $catOffset = 0; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categoryData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category['count'] > 0): ?>
                        <?php
                            $catCircum = 2 * M_PI * 40;
                            $catPct = $totalCategoryArticles > 0 ? ($category['count'] / $totalCategoryArticles) * 100 : 0;
                            $catDash = ($catCircum * $catPct) / 100;
                            $catGap = $catCircum - $catDash;
                            $catDashOffset = -($catCircum * $catOffset) / 100;
                            $catOffset += $catPct;
                            // Colors matching theme
                            $fillColors = ['#6366f1', '#06b6d4', '#f59e0b', '#ec4899', '#10b981']; 
                            $strokeColor = $fillColors[$index % 5];
                        ?>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="<?php echo e($strokeColor); ?>"
                            stroke-width="12" 
                            stroke-dasharray="<?php echo e($catDash); ?> <?php echo e($catGap); ?>" 
                            stroke-dashoffset="<?php echo e($catDashOffset); ?>"
                            stroke-linecap="butt" 
                            class="transition-all duration-500 hover:opacity-80"></circle>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-bold text-surface-900 dark:text-white"><?php echo e($stats['total_articles']); ?></span>
            </div>
        </div>
        
        
        <div class="flex-1 grid grid-cols-1 gap-1.5 overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categoryData->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $fillColors = ['bg-primary-500', 'bg-accent-cyan', 'bg-accent-amber', 'bg-pink-500', 'bg-emerald-500']; ?>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 truncate">
                        <span class="w-2 h-2 rounded-full <?php echo e($fillColors[$index % 5]); ?>"></span>
                        <span class="text-surface-600 dark:text-surface-400 truncate max-w-[80px]"><?php echo e($category['name']); ?></span>
                    </div>
                    <span class="font-semibold text-surface-900 dark:text-white"><?php echo e($category['count']); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-xs text-surface-400 italic">Belum ada data</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categoryData->count() > 4): ?>
                <p class="text-[10px] text-surface-400 mt-1">+<?php echo e($categoryData->count() - 4); ?> lainnya</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard\partials\category-distribution.blade.php ENDPATH**/ ?>