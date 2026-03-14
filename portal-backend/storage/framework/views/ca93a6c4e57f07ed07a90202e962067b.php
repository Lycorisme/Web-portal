<?php
    $icons = [
        'success' => ['icon' => 'fa-check-circle', 'color' => 'text-green-500', 'bg' => 'bg-green-100'],
        'error' => ['icon' => 'fa-times-circle', 'color' => 'text-red-500', 'bg' => 'bg-red-100'],
        'warning' => ['icon' => 'fa-exclamation-triangle', 'color' => 'text-amber-500', 'bg' => 'bg-amber-100'],
        'info' => ['icon' => 'fa-info-circle', 'color' => 'text-blue-500', 'bg' => 'bg-blue-100'],
    ];
    $config = $icons[$type] ?? $icons['info'];
?>

<div class="flex gap-3 px-5 py-4 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer <?php echo e($unread ? 'bg-blue-50/50' : ''); ?>">
    <div class="flex-shrink-0 w-10 h-10 rounded-full <?php echo e($config['bg']); ?> flex items-center justify-center <?php echo e($config['color']); ?>">
        <i class="fa-solid <?php echo e($config['icon']); ?>"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-slate-800 truncate"><?php echo e($title); ?></p>
        <p class="text-xs text-slate-500 mt-0.5"><?php echo e($message); ?></p>
        <p class="text-xs text-slate-400 mt-1"><?php echo e($time); ?></p>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unread): ?>
        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\partials\notification-item.blade.php ENDPATH**/ ?>