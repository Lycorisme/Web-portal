
<template x-teleport="body">
    <div 
        x-show="showFormModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        
        <div 
            x-show="showFormModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity"
            @click="closeFormModal()"
        ></div>

        
        <div class="flex min-h-full items-end sm:items-center justify-center sm:p-4">
            <div 
                x-show="showFormModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-6xl h-[95vh] sm:h-[90vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                
                <?php echo $__env->make('galleries.partials.form-modal.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <div class="flex flex-1 overflow-hidden relative">
                    
                    <?php echo $__env->make('galleries.partials.form-modal.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    
                    <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 relative scroll-smooth" id="form-scroll-container">
                        <form id="galleryForm" @submit.prevent="submitForm()" class="p-4 sm:p-8 pb-10 max-w-4xl mx-auto space-y-6 sm:space-y-8">
                            
                            <?php echo $__env->make('galleries.partials.form-modal.tab-basic', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            
                            <?php echo $__env->make('galleries.partials.form-modal.tab-media', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            
                            <?php echo $__env->make('galleries.partials.form-modal.tab-settings', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\galleries\partials\form-modal.blade.php ENDPATH**/ ?>