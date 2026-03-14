<?php $__env->startSection('content'); ?>
    <div class="judul">
        <h3>LAPORAN DATA GALLERY / MEDIA</h3>
    </div>

    
    <div style="margin-bottom: 20px; font-size: 10pt;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Nomor Dokumen</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;"><?php echo e($doc_number); ?>/<?php echo e(strtoupper(str_replace(' ', '-', $settings['site_name'] ?? 'INSTANSI'))); ?>/<?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('M')); ?>/<?php echo e(date('Y')); ?></td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Periode Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($has_date_filter ?? false): ?>
                        <?php echo e($date_from ?: '-'); ?> s/d <?php echo e($date_to ?: '-'); ?>

                    <?php else: ?>
                        Semua Data
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Petugas Penarik Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;"><?php echo e(Auth::user()->name ?? 'System'); ?></td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Jumlah Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;"><?php echo e($items->count()); ?> media</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 25%; text-align: left; padding-left: 8px;">Judul</th>
                <th style="width: 15%;">Album</th>
                <th style="width: 10%;">Tipe Media</th>
                <th style="width: 15%; text-align: left; padding-left: 8px;">Uploader</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 14%;">Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="number"><?php echo e($index + 1); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e($gallery->title ?? '-'); ?></td>
                    <td class="center"><?php echo e($gallery->album ?? '-'); ?></td>
                    <td class="center">
                        <span class="badge <?php echo e($gallery->media_type === 'image' ? 'badge-info' : 'badge-warning'); ?>">
                            <?php echo e(strtoupper($gallery->media_type)); ?>

                        </span>
                    </td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e($gallery->uploader->name ?? '-'); ?></td>
                    <td class="center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gallery->is_published): ?>
                            <span class="badge badge-success">PUBLISH</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">DRAFT</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="center"><?php echo e($gallery->created_at->format('d/m/Y')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="center">Tidak ada data gallery pada periode ini.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reports.pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\reports\pdf\galleries.blade.php ENDPATH**/ ?>