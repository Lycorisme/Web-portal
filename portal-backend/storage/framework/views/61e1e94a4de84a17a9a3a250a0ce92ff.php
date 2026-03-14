<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

    
    <div class="lg:col-span-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredArticle): ?>
            <a href="<?php echo e(route('public.article.show', $featuredArticle->slug)); ?>" class="group relative overflow-hidden rounded-[2rem] bg-slate-200 h-[400px] md:h-[550px] cursor-pointer shadow-2xl block">
                <img src="<?php echo e($featuredArticle->thumbnail ? asset('storage/' . $featuredArticle->thumbnail) : 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=1200'); ?>"
                    class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110"
                    alt="<?php echo e($featuredArticle->title); ?>">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="absolute bottom-0 p-6 md:p-10">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredArticle->categoryRelation): ?>
                        <span class="bg-indigo-500 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4 inline-block"><?php echo e($featuredArticle->categoryRelation->name); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h1 class="text-2xl md:text-5xl font-extrabold text-white leading-tight mb-4 group-hover:text-indigo-300 transition">
                        <?php echo e($featuredArticle->title); ?>

                    </h1>
                    <p class="text-slate-300 text-sm md:text-lg max-w-2xl line-clamp-2"><?php echo e($featuredArticle->excerpt ?? Str::limit(strip_tags($featuredArticle->content), 150)); ?></p>
                </div>
            </a>
        <?php else: ?>
            <div class="group relative overflow-hidden rounded-[2rem] bg-slate-200 h-[400px] md:h-[550px] cursor-pointer shadow-2xl flex items-center justify-center">
                <p class="text-slate-500 text-xl">Belum ada artikel unggulan</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="lg:col-span-4 flex flex-col gap-6">
        <h3 class="text-xl font-extrabold flex items-center gap-2">
            <span class="w-2 h-6 bg-rose-500 rounded-full"></span>
            Trending Sekarang
        </h3>
        <div class="space-y-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $trendingArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $colors = ['text-rose-500', 'text-amber-500', 'text-emerald-500', 'text-sky-500', 'text-indigo-500'];
                    $colorClass = $colors[$index % count($colors)];
                ?>
                <a href="<?php echo e(route('public.article.show', $article->slug)); ?>" class="flex gap-4 group cursor-pointer items-center <?php echo e($index < count($trendingArticles) - 1 ? 'border-b border-slate-100 pb-4' : ''); ?>">
                    <span class="text-3xl font-black text-slate-200 group-hover:text-rose-500 transition"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                    <div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->categoryRelation): ?>
                            <span class="<?php echo e($colorClass); ?> font-bold text-[10px] uppercase"><?php echo e($article->categoryRelation->name); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <h4 class="font-bold text-slate-800 group-hover:text-indigo-600 transition leading-snug"><?php echo e(Str::limit($article->title, 60)); ?></h4>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-slate-400 text-sm">Belum ada artikel trending</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="mt-4 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white relative overflow-hidden">
            <p class="text-xs uppercase font-bold text-slate-400 mb-2">Iklan</p>
            <p class="font-bold text-lg mb-4">Butuh Hosting Cepat? Cek Penawaran Kami!</p>
            <button class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase">Cek Sekarang</button>
            <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-indigo-500/20 rounded-full blur-2xl"></div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\public\partials\hero-section.blade.php ENDPATH**/ ?>