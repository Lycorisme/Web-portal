<div x-show="activeTab === 'media'" class="space-y-6">
    <?php echo $__env->make('galleries.partials.form-modal.media-type-selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('galleries.partials.form-modal.image-upload', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('galleries.partials.form-modal.video-url', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\galleries\partials\form-modal\tab-media.blade.php ENDPATH**/ ?>