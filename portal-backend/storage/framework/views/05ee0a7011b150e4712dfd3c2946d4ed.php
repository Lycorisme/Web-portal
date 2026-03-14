
<aside class="w-full lg:w-80 flex-shrink-0 min-w-0">
    <div class="lg:sticky lg:top-20 space-y-4 sm:space-y-6">
        
        
        <div class="bg-slate-900/80 backdrop-blur-md rounded-[32px] border border-white/5 p-6 sm:p-8 shadow-xl overflow-hidden relative group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-widest flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                Artikel Serupa
            </h4>
            <div class="space-y-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('public.article.show', $related->slug)); ?>" wire:navigate class="group/item flex gap-4 items-start">
                        <span class="text-2xl font-black text-slate-800 group-hover/item:text-emerald-500 transition-colors duration-300 w-8 flex-shrink-0 leading-none -mt-1">
                            <?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?>

                        </span>
                        <div class="min-w-0 flex-1 border-b border-white/5 pb-4 last:border-0 last:pb-0">
                            <h5 class="text-sm font-bold text-slate-300 group-hover/item:text-emerald-400 line-clamp-2 leading-snug transition-colors break-words mb-2">
                                <?php echo e($related->title); ?>

                            </h5>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider group-hover/item:text-slate-400 transition-colors">
                                    <?php echo e($related->likes_count ?? 0); ?> Likes
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-6">
                        <p class="text-xs text-slate-500 font-medium italic">Tidak ada artikel terkait</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <?php
            $allTags = \App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->take(15)->get();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($allTags->count() > 0): ?>
            <div class="bg-slate-900/80 backdrop-blur-md rounded-[32px] border border-white/5 p-6 sm:p-8 shadow-xl relative overflow-hidden">
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-500/5 rounded-full blur-xl -ml-12 -mb-12 pointer-events-none"></div>
                
                <h4 class="text-sm font-bold text-white mb-6 uppercase tracking-widest flex items-center gap-3">
                     <span class="w-1.5 h-1.5 rounded-full bg-blue-400 shadow-[0_0_10px_#60a5fa]"></span>
                    Jelajahi Tag
                </h4>
                <div class="flex flex-wrap gap-2 relative z-10">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('public.articles', ['tag' => $tag->slug])); ?>" wire:navigate 
                           class="px-3 py-1.5 bg-slate-950 border border-white/10 hover:border-emerald-500 text-slate-400 hover:text-emerald-400 text-[11px] font-bold uppercase tracking-wider rounded-lg transition-all duration-300 hover:shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                            #<?php echo e($tag->name); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
    </div>
</aside>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\public\article\partials\sidebar.blade.php ENDPATH**/ ?>