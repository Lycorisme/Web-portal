<?php
    $changeColors = [
        'up' => 'text-green-500',
        'down' => 'text-red-500',
        'neutral' => 'text-slate-400',
    ];
    $changeColor = $changeColors[$changeType] ?? $changeColors['neutral'];
?>

<div class="bg-white p-5 rounded-xl shadow-sm border <?php echo e($highlight ? 'border-red-100' : 'border-slate-100'); ?> relative overflow-hidden group hover:shadow-lg transition-all duration-150">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($highlight): ?>
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full z-0 group-hover:scale-125 transition-transform duration-150"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="flex justify-between items-start z-10 relative">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide <?php echo e($highlight ? 'text-red-500' : 'text-slate-500'); ?>">
                <?php echo e($title); ?>

            </p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1"><?php echo e($value); ?></h3>
            <p class="text-xs mt-2 font-medium <?php echo e($changeColor); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($changeType === 'up'): ?>
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i>
                <?php elseif($changeType === 'down'): ?>
                    <i class="fa-solid fa-arrow-trend-down mr-1"></i>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php echo e($change); ?>

            </p>
        </div>
        <div class="<?php echo e($iconBg); ?> p-3 rounded-lg <?php echo e($iconColor); ?>">
            <i class="<?php echo e($icon); ?> text-xl"></i>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\partials\stat-card.blade.php ENDPATH**/ ?>