
<?php
    $siteName = \App\Models\SiteSetting::get('site_name', 'BTIKP');
    $siteTagline = \App\Models\SiteSetting::get('site_tagline', 'Portal Berita dan Informasi Official');
    $siteAddress = \App\Models\SiteSetting::get('site_address', '');
    $sitePhone = \App\Models\SiteSetting::get('site_phone', '');
    $siteEmail = \App\Models\SiteSetting::get('site_email', '');
    $facebookUrl = \App\Models\SiteSetting::get('facebook_url', '');
    $instagramUrl = \App\Models\SiteSetting::get('instagram_url', '');
    $twitterUrl = \App\Models\SiteSetting::get('twitter_url', '');
    $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', '');
    $logoUrl = \App\Models\SiteSetting::get('logo_url');
    if (empty($logoUrl)) {
        $logoUrl = \App\Models\SiteSetting::get('site_logo', '');
    }
    
    $footerText = \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.');
    
    // Fetch categories for footer
    $footerCategories = \App\Models\Category::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->take(6)
        ->get();
?>

<footer class="relative bg-slate-950 pt-32 pb-12 overflow-hidden border-t border-white/5">
    
    
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px] mix-blend-screen animate-float-slow"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-600/5 rounded-full blur-[120px] mix-blend-screen animate-float-slow" style="animation-delay: 2s"></div>
        
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-16 mb-20">
            
            
            <div class="lg:col-span-5 space-y-8">
                <a href="<?php echo e(route('public.home')); ?>" wire:navigate class="inline-flex items-center gap-4 group">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($logoUrl)): ?>
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                            <img src="<?php echo e(asset($logoUrl)); ?>" alt="<?php echo e($siteName); ?>" class="h-14 w-auto relative z-10 transition-transform duration-500 group-hover:scale-105">
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex flex-col">
                        <span class="font-display font-bold text-3xl text-white leading-none tracking-tight group-hover:text-emerald-400 transition-colors duration-300">
                            <?php echo e($siteName); ?>

                        </span>
                        <span class="text-[0.65rem] font-bold text-emerald-500/80 uppercase tracking-[0.25em] mt-1">
                            <?php echo e($siteTagline); ?>

                        </span>
                    </div>
                </a>
                
                <p class="text-slate-400 text-sm leading-relaxed max-w-md font-medium">
                    Platform digital resmi yang menyajikan berita terkini, artikel edukatif, dan dokumentasi visual kegiatan secara transparan dan akuntabel.
                </p>

                
                <div class="flex items-center gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facebookUrl): ?>
                        <a href="<?php echo e($facebookUrl); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-900/50 border border-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-[#1877F2] hover:border-[#1877F2] transition-all duration-300 group shadow-lg hover:shadow-[#1877F2]/20 hover:-translate-y-1">
                            <i class="fab fa-facebook-f text-lg group-hover:scale-110 transition-transform"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($instagramUrl): ?>
                        <a href="<?php echo e($instagramUrl); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-900/50 border border-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:border-transparent transition-all duration-300 group shadow-lg hover:shadow-[#dc2743]/20 hover:-translate-y-1">
                            <i class="fab fa-instagram text-lg group-hover:scale-110 transition-transform"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($twitterUrl): ?>
                        <a href="<?php echo e($twitterUrl); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-900/50 border border-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-black hover:border-white/20 transition-all duration-300 group shadow-lg hover:shadow-white/10 hover:-translate-y-1">
                            <i class="fab fa-x-twitter text-lg group-hover:scale-110 transition-transform"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($youtubeUrl): ?>
                        <a href="<?php echo e($youtubeUrl); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-slate-900/50 border border-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-[#FF0000] hover:border-[#FF0000] transition-all duration-300 group shadow-lg hover:shadow-[#FF0000]/20 hover:-translate-y-1">
                            <i class="fab fa-youtube text-lg group-hover:scale-110 transition-transform"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-3 gap-10">
                
                
                <div class="space-y-6">
                    <h4 class="text-white font-display font-bold text-lg tracking-tight">Menu Utama</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="<?php echo e(route('public.home')); ?>" wire:navigate class="group flex items-center gap-3 text-slate-400 hover:text-white transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors"></span>
                                <span class="text-sm font-medium">Beranda</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('public.articles')); ?>" wire:navigate class="group flex items-center gap-3 text-slate-400 hover:text-white transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors"></span>
                                <span class="text-sm font-medium">Berita</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('public.gallery')); ?>" wire:navigate class="group flex items-center gap-3 text-slate-400 hover:text-white transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors"></span>
                                <span class="text-sm font-medium">Galeri</span>
                            </a>
                        </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <li>
                                <a href="<?php echo e(route('dashboard')); ?>" wire:navigate class="group flex items-center gap-3 text-slate-400 hover:text-emerald-400 transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors"></span>
                                    <span class="text-sm font-medium">Dashboard</span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo e(route('login')); ?>" wire:navigate class="group flex items-center gap-3 text-slate-400 hover:text-emerald-400 transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors"></span>
                                    <span class="text-sm font-medium">Masuk</span>
                                </a>
                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                
                <div class="space-y-6">
                    <h4 class="text-white font-display font-bold text-lg tracking-tight">Topik Populer</h4>
                    <ul class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $footerCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(route('public.articles', ['kategori' => $cat->slug])); ?>" wire:navigate class="group flex items-center gap-3 text-slate-400 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-blue-400 transition-colors"></span>
                                    <span class="text-sm font-medium"><?php echo e($cat->name); ?></span>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                
                <div class="space-y-6">
                    <h4 class="text-white font-display font-bold text-lg tracking-tight">Hubungi Kami</h4>
                    <ul class="space-y-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($siteAddress): ?>
                            <li class="flex items-start gap-4 text-slate-400 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center shrink-0 group-hover:border-emerald-500/30 group-hover:text-emerald-400 transition-colors">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <span class="text-sm leading-relaxed"><?php echo e($siteAddress); ?></span>
                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sitePhone): ?>
                            <li class="flex items-center gap-4 text-slate-400 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center shrink-0 group-hover:border-emerald-500/30 group-hover:text-emerald-400 transition-colors">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <span class="text-sm font-mono"><?php echo e($sitePhone); ?></span>
                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($siteEmail): ?>
                            <li class="flex items-center gap-4 text-slate-400 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center shrink-0 group-hover:border-emerald-500/30 group-hover:text-emerald-400 transition-colors">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <span class="text-sm"><?php echo e($siteEmail); ?></span>
                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-slate-500 text-xs font-medium tracking-wide">
                <?php echo $footerText; ?>

            </p>
            
            <div class="flex items-center gap-6">
                <a href="#" class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">Privacy Policy</a>
                <span class="w-1 h-1 rounded-full bg-slate-800"></span>
                <a href="#" class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\public\layouts\partials\public-footer.blade.php ENDPATH**/ ?>