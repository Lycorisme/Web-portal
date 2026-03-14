<?php $__env->startSection('content'); ?>
    <div class="judul">
        <h3>LAPORAN INTERAKSI PUBLIK</h3>
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
        </table>
    </div>

    
    <div style="margin-bottom: 20px;">
        <table style="width: 50%; border: none; font-size: 10pt;">
            <tr>
                <td style="border: none; padding: 2px 0; width: 50%;"><strong>Total Artikel Terpublikasi</strong></td>
                <td style="border: none; padding: 2px 0; width: 5%;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e(number_format($summary['total_articles'])); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Views Keseluruhan</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e(number_format($summary['total_views'])); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Komentar</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e(number_format($summary['total_comments'])); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Komentar Spam</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e(number_format($summary['total_spam'])); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Likes</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e(number_format($summary['total_likes'])); ?></td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 28%; text-align: left; padding-left: 8px;">Judul Artikel</th>
                <th style="width: 8%;">Views</th>
                <th style="width: 10%;">Total Komentar</th>
                <th style="width: 10%;">Komentar Spam</th>
                <th style="width: 10%;">Total Like</th>
                <th style="width: 18%; text-align: left; padding-left: 8px;">Komentator Terbanyak</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="number"><?php echo e($index + 1); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e(\Illuminate\Support\Str::limit($article->title, 50)); ?></td>
                    <td class="center"><?php echo e(number_format($article->views ?? 0)); ?></td>
                    <td class="center"><?php echo e($article->comments_count); ?></td>
                    <td class="center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->spam_comments_count > 0): ?>
                            <span class="badge badge-danger"><?php echo e($article->spam_comments_count); ?></span>
                        <?php else: ?>
                            0
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="center"><?php echo e($article->likes_count); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e($article->top_commenter_name); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="center">Tidak ada data interaksi pada periode ini.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reports.pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\reports\pdf\interactions.blade.php ENDPATH**/ ?>