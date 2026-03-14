<?php
    $statusConfig = [
        'published' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Terbit'],
        'draft' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Draft'],
        'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Pending'],
    ];
    $config = $statusConfig[$status] ?? $statusConfig['draft'];
?>

<tr class="hover:bg-slate-50 transition-colors duration-150">
    <td class="px-6 py-4">
        <p class="text-sm font-medium text-slate-800 line-clamp-1"><?php echo e($title); ?></p>
    </td>
    <td class="px-6 py-4 hidden md:table-cell">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
            <?php echo e($category); ?>

        </span>
    </td>
    <td class="px-6 py-4 text-sm text-slate-500 hidden lg:table-cell"><?php echo e($author); ?></td>
    <td class="px-6 py-4">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($config['bg']); ?> <?php echo e($config['text']); ?>">
            <?php echo e($config['label']); ?>

        </span>
    </td>
    <td class="px-6 py-4 text-sm text-slate-500 hidden sm:table-cell"><?php echo e($views); ?></td>
    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end gap-2">
            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-150">
                <i class="fa-solid fa-eye text-sm"></i>
            </button>
            <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-150">
                <i class="fa-solid fa-pen text-sm"></i>
            </button>
            <button 
                onclick="showAlert('danger', 'Hapus Artikel', 'Apakah Anda yakin ingin menghapus artikel ini?', function() { showToast('success', 'Berhasil', 'Artikel berhasil dihapus!'); })"
                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
            >
                <i class="fa-solid fa-trash text-sm"></i>
            </button>
        </div>
    </td>
</tr>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\partials\article-row.blade.php ENDPATH**/ ?>