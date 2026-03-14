<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Admin'); ?> - Portal Berita BTIKP</title>
    <link rel="icon" id="dynamic-favicon" href="<?php echo e(\App\Models\SiteSetting::get('favicon_url', asset('favicon.ico'))); ?>">

    
    <script src="https://cdn.tailwindcss.com"></script>

    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    
    <?php echo $__env->make('layouts.partials.head-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('layouts.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body 
    x-data="appState()" 
    x-init="init()"
    :class="{ 'dark': darkMode }"
    class="font-jakarta bg-surface-50 dark:bg-surface-950 text-surface-900 dark:text-surface-100 transition-colors duration-300 overflow-x-hidden antialiased"
>

    
    <?php echo $__env->make('layouts.partials.loading-screen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('layouts.partials.background', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="relative z-10 flex min-h-screen">

        
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
        </div>
        
        <div 
            :class="sidebarOpen ? 'lg:w-72' : 'lg:w-20'"
            class="hidden lg:block flex-shrink-0 transition-all duration-300 bg-white/95 dark:bg-surface-900/95 border-r border-surface-200/50 dark:border-surface-800/50"
        >
            
            <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
        
        <div class="lg:hidden">
            <?php echo $__env->make('partials.sidebar-mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <main id="main-content" class="flex-1 min-w-0 overflow-hidden transition-all duration-300 min-h-screen flex flex-col">

            
            <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <div class="flex-1 p-4 lg:p-8">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            
            <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </main>
    </div>

    
    <?php echo $__env->make('layouts.partials.custom-toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('partials.command-palette', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('layouts.partials.footer-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views/layouts/app.blade.php ENDPATH**/ ?>