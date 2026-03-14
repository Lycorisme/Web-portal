<?php $__env->startSection('meta_title', 'Profil Saya'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen pt-28 pb-16 relative" x-data="profilePage()">
    
    
    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-6">
        
        
        <?php echo $__env->make('public.profile.partials.profile-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            
            <div class="lg:col-span-3 sticky top-32 z-20">
                <?php echo $__env->make('public.profile.partials.navigation-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div class="lg:col-span-9 min-h-[400px]">
                
                
                <div x-show="activeTab === 'settings'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    <?php echo $__env->make('public.profile.partials.settings-tab', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                
                <div x-show="activeTab === 'activity'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    <?php echo $__env->make('public.profile.partials.activity-tab', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                
                <div x-show="activeTab === 'security'"
                     x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-500"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     style="display: none;"
                     class="space-y-8">
                    <?php echo $__env->make('public.profile.partials.security-tab', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('public.profile.partials.profile-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('public.layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\public\profile\index.blade.php ENDPATH**/ ?>