<button type="submit" :disabled="loading.<?php echo e($key); ?>" 
    class="w-full px-4 py-2.5 bg-theme-gradient text-white font-medium rounded-xl shadow-theme hover:shadow-theme-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50">
    <svg x-show="loading.<?php echo e($key); ?>" class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
    <svg x-show="!loading.<?php echo e($key); ?>" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
    </svg>
    <span x-text="loading.<?php echo e($key); ?> ? 'Generating...' : 'Unduh PDF'"></span>
</button>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\reports\partials\submit-button.blade.php ENDPATH**/ ?>