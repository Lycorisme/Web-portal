<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Portal Berita dan Informasi BTIKP'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', 'berita, informasi, BTIKP, portal'); ?>">
    
    <title><?php echo $__env->yieldContent('meta_title', 'Beranda'); ?> - <?php echo e($siteSettings['site_name'] ?? 'BTIKP Portal'); ?></title>
    
    
    <?php
        $faviconUrl = \App\Models\SiteSetting::get('favicon_url', '');
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($faviconUrl): ?>
        <link rel="icon" href="<?php echo e($faviconUrl); ?>" type="image/x-icon">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#151e2e',
                            950: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    
    

    
    <?php echo $__env->make('public.layouts.partials.public-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-slate-950 text-slate-200 antialiased selection:bg-emerald-500/30 selection:text-emerald-400 overflow-x-hidden">

    
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px] animate-float-slow"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-emerald-500/10 rounded-full blur-[120px] animate-float-slow" style="animation-delay: 2s"></div>
        <div class="absolute top-[40%] left-[30%] w-[40%] h-[40%] bg-blue-500/10 rounded-full blur-[150px] animate-float" style="animation-delay: -3s"></div>
    </div>

    <div class="relative flex flex-col min-h-screen">
        
        <?php echo $__env->make('public.layouts.partials.public-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
        
        <main class="flex-grow relative z-10 w-full animate-enter">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    
        
        <?php echo $__env->make('public.layouts.partials.public-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <?php echo $__env->make('public.layouts.partials.public-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <div x-data="{ show: false }" 
         @scroll.window="show = (window.pageYOffset > 300)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed bottom-8 right-8 z-50">
        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-500 hover:bg-emerald-400 text-white shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\public\layouts\public.blade.php ENDPATH**/ ?>