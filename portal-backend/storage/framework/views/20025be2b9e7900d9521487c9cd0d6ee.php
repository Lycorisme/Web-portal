<?php
    $colors = [
        'error' => 'bg-red-500',
        'success' => 'bg-green-500',
        'warning' => 'bg-orange-500',
        'info' => 'bg-blue-500',
    ];
    $bgColor = $colors[$type] ?? $colors['info'];
?>

<div class="flex gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors duration-150 border-b border-slate-50 last:border-0">
    <div class="mt-1">
        <div class="w-2 h-2 rounded-full <?php echo e($bgColor); ?>"></div>
    </div>
    <div>
        <p class="text-xs font-bold text-slate-700"><?php echo e($title); ?></p>
        <p class="text-[10px] text-slate-400 mt-0.5">
            <?php echo e($detail); ?> • <span class="text-slate-500"><?php echo e($time); ?></span>
        </p>
    </div>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\partials\security-log-item.blade.php ENDPATH**/ ?>