<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 shadow-sm overflow-hidden flex flex-col">
    <div class="p-5 border-b border-surface-200 dark:border-surface-800 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-lg text-surface-900 dark:text-white">
                <?php echo e($stats['is_author'] ?? false ? 'Berita Saya' : 'Berita Terbaru'); ?>

            </h2>
            <p class="text-sm text-surface-500 dark:text-surface-400">
                <?php echo e($stats['is_author'] ?? false ? 'Artikel yang Anda tulis' : 'Update publikasi terakhir'); ?>

            </p>
        </div>
        <a href="<?php echo e(route('articles')); ?>" class="group inline-flex items-center gap-1 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700">
            Semua Berita
            <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
        </a>
    </div>

    <div class="divide-y divide-surface-100 dark:divide-surface-800/50">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div 
            class="group p-4 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors flex gap-4 items-start cursor-pointer"
            @click="openArticleModal('<?php echo e($article->id); ?>')"
        >
             
             <div class="shrink-0 w-20 h-16 sm:w-24 sm:h-20 bg-surface-100 dark:bg-surface-800 rounded-lg overflow-hidden relative">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->thumbnail): ?>
                    <?php
                        $thumbUrl = $article->thumbnail;
                        if (str_starts_with($thumbUrl, '/storage/') || str_starts_with($thumbUrl, 'storage/')) {
                            $thumbUrl = asset($thumbUrl);
                        } elseif (!str_starts_with($thumbUrl, 'http')) {
                            $thumbUrl = asset('storage/' . $thumbUrl);
                        }
                    ?>
                    <img src="<?php echo e($thumbUrl); ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden items-center justify-center w-full h-full text-surface-300 absolute inset-0">
                        <i data-lucide="image" class="w-8 h-8"></i>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center w-full h-full text-surface-300">
                        <i data-lucide="image" class="w-8 h-8"></i>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->status === 'published'): ?>
                    <div class="absolute bottom-1 right-1 bg-black/50 backdrop-blur-sm text-white text-[10px] px-1.5 py-0.5 rounded">
                        <i data-lucide="eye" class="w-3 h-3 inline-block -mt-0.5"></i> <?php echo e($article->views); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
             </div>

             
             <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-sm sm:text-base font-semibold text-surface-900 dark:text-white line-clamp-2 leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                        <?php echo e($article->title); ?>

                    </h3>
                    
                    <?php
                        $badgeClass = match($article->status) {
                            'published' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                            'draft' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                            'pending' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                            default => 'bg-surface-100 text-surface-600'
                        };
                    ?>
                    <span class="shrink-0 text-[10px] font-bold uppercase px-2 py-1 rounded <?php echo e($badgeClass); ?>">
                        <?php echo e($article->status); ?>

                    </span>
                </div>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400 mt-1 line-clamp-1">
                     <?php echo e(Str::limit(strip_tags($article->content), 80)); ?>

                </p>
                <div class="flex items-center gap-3 mt-2.5 text-xs text-surface-400">
                    <span class="flex items-center gap-1"><?php echo e($article->author->name ?? 'Admin'); ?></span>
                    <span class="w-1 h-1 bg-surface-300 rounded-full"></span>
                    <span><?php echo e($article->updated_at->diffForHumans()); ?></span>
                </div>
             </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
            <div class="w-20 h-20 bg-surface-50 dark:bg-surface-800 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="file-plus" class="w-10 h-10 text-surface-300 dark:text-surface-600"></i>
            </div>
            <h3 class="text-surface-900 dark:text-white font-medium mb-1">Belum Ada Berita</h3>
            <p class="text-sm text-surface-500 dark:text-surface-400 mb-5 max-w-xs mx-auto">
                Mulai publikasi konten pertama Anda untuk mengisi dashboard ini.
            </p>
            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Berita Sekarang
            </a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\dashboard\partials\recent-news.blade.php ENDPATH**/ ?>