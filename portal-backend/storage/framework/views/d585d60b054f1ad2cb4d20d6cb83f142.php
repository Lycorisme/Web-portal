<?php $__env->startSection('content'); ?>
    <div class="judul">
        <h3>LAPORAN DATA BERITA</h3>
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
                <td style="border: none; padding: 1px;"><?php echo e($items->count()); ?> berita</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 30%; text-align: left; padding-left: 8px;">Judul</th>
                <th style="width: 13%;">Kategori</th>
                <th style="width: 13%; text-align: left; padding-left: 8px;">Penulis</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 8%;">Views</th>
                <th style="width: 12%;">Tanggal Publish</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="number"><?php echo e($index + 1); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e($article->title); ?></td>
                    <td class="center"><?php echo e($article->categoryRelation->name ?? '-'); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e($article->author->name ?? '-'); ?></td>
                    <td class="center">
                        <?php
                            $statusClass = match($article->status) {
                                'published' => 'badge-success',
                                'draft' => 'badge-secondary',
                                'pending' => 'badge-warning',
                                'rejected' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                        ?>
                        <span class="badge <?php echo e($statusClass); ?>"><?php echo e(strtoupper($article->status)); ?></span>
                    </td>
                    <td class="center"><?php echo e(number_format($article->views ?? 0)); ?></td>
                    <td class="center">
                        <?php echo e($article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d/m/Y') : '-'); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="center">Tidak ada data berita pada periode ini.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reports.pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\reports\pdf\articles.blade.php ENDPATH**/ ?>