<div x-show="activeTab === 'basic'" class="space-y-6">
    <?php echo $__env->make('galleries.partials.form-modal.title-description', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('galleries.partials.form-modal.album-location', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('galleries.partials.form-modal.event-date', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\galleries\partials\form-modal\tab-basic.blade.php ENDPATH**/ ?>