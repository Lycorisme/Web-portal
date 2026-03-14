<?php $__env->startSection('title', 'Kelola Berita'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="articleApp()" x-init="init()">
    
    <?php echo $__env->make('articles.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            
            <?php echo $__env->make('articles.partials.filter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php echo $__env->make('articles.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php echo $__env->make('articles.partials.pagination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    
    <?php echo $__env->make('articles.partials.bulk-action-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('articles.partials.action-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('articles.partials.form-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('articles.partials.detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('articles.partials.statistics-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('articles.partials.activity-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    trix-toolbar [data-trix-button-group="file-tools"] {
        display: none;
    }
    trix-editor {
        min-height: 300px;
    }
    .dark trix-editor {
        background-color: rgb(23 23 23); /* surface-900 */
        border-color: rgb(64 64 64); /* surface-700 */
        color: white;
    }
    .dark trix-toolbar {
        background-color: rgb(38 38 38); /* surface-800 */
        border-color: rgb(64 64 64);
    }
    .dark trix-toolbar .trix-button {
        background-color: rgb(64 64 64);
        border-bottom: none;
        color: white;
    }
    .dark trix-toolbar .trix-button.trix-active {
        background-color: rgb(16 185 129); /* theme-500 */
    }
    
    /* Cropper.js Custom Styles */
    .cropper-container {
        border-radius: 1rem;
        overflow: hidden;
    }
    .cropper-view-box,
    .cropper-face {
        border-radius: 0;
    }
    .cropper-view-box {
        outline: 2px solid rgb(16 185 129);
        outline-color: rgb(16 185 129);
    }
    .cropper-line {
        background-color: rgb(16 185 129);
    }
    .cropper-point {
        background-color: rgb(16 185 129);
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .cropper-point.point-se {
        width: 12px;
        height: 12px;
    }
    .cropper-dashed {
        border-color: rgba(255, 255, 255, 0.5);
    }
    .cropper-modal {
        background-color: rgba(0, 0, 0, 0.7);
    }
    
    /* Zoom Range Slider */
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background: rgb(16 185 129);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }
    input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        background: rgb(16 185 129);
        border-radius: 50%;
        cursor: pointer;
        border: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<?php echo $__env->make('articles.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\articles\index.blade.php ENDPATH**/ ?>