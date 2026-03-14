<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.name', 'Portal')); ?></title>
    
    
    <link rel="icon" href="<?php echo e(\App\Models\SiteSetting::get('favicon_url', asset('favicon.ico'))); ?>" type="image/x-icon">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#151e2e',
                            950: '#020617',
                        },
                        primary: {
                            400: '#34d399',
                            500: '#10b981', 
                            600: '#059669',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    backgroundImage: {
                        'grid-white': "linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px)",
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen flex items-center justify-center overflow-hidden relative antialiased selection:bg-<?php echo $__env->yieldContent('accent-color', 'emerald'); ?>-500/30 selection:text-<?php echo $__env->yieldContent('accent-color', 'emerald'); ?>-400">

    
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        
        
        <div class="absolute inset-0 bg-grid-white bg-[length:50px_50px] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)] opacity-50"></div>

        
        <?php echo $__env->yieldContent('background-blobs'); ?>
    </div>

    
    <div class="relative z-10 w-full max-w-xl px-4 text-center">
        
        
        <div class="mb-10 flex justify-center animate-float">
            <?php
                $logoUrl = \App\Models\SiteSetting::get('logo_url');
                $siteName = \App\Models\SiteSetting::get('site_name', 'Portal');
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                <div class="relative">
                    <div class="absolute inset-0 bg-<?php echo $__env->yieldContent('accent-color', 'emerald'); ?>-500/20 blur-xl rounded-full"></div>
                    <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>" class="relative h-12 w-auto object-contain drop-shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                </div>
            <?php else: ?>
                <div class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl shadow-<?php echo $__env->yieldContent('accent-color', 'emerald'); ?>-500/5">
                   <div class="w-8 h-8 rounded-lg bg-<?php echo $__env->yieldContent('accent-color', 'emerald'); ?>-500 flex items-center justify-center text-white font-bold font-display text-lg shadow-lg shadow-<?php echo $__env->yieldContent('accent-color', 'emerald'); ?>-500/30">
                       <?php echo e(substr($siteName, 0, 1)); ?>

                   </div>
                   <span class="text-lg font-bold font-display tracking-tight text-white/90"><?php echo e($siteName); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php echo $__env->yieldContent('icon-section'); ?>

        
        <div class="space-y-6 mb-12">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

        
        <?php echo $__env->yieldContent('actions'); ?>
        
    </div>

    
    <div class="fixed bottom-6 w-full text-center">
        <p class="text-[10px] text-slate-600 uppercase tracking-[0.2em] font-medium font-display opacity-60 hover:opacity-100 transition-opacity">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(\App\Models\SiteSetting::get('site_name', 'Portal')); ?>. All rights reserved.
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\errors\layout.blade.php ENDPATH**/ ?>