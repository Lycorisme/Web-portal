<?php $__env->startSection('content'); ?>
    <div class="judul">
        <h3>LAPORAN KEAMANAN & IP TERBLOKIR</h3>
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
        <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">A. Ringkasan Keamanan</h4>
        <table style="width: 50%; border: none; font-size: 10pt;">
            <tr>
                <td style="border: none; padding: 2px 0; width: 55%;"><strong>Total IP Terblokir (Aktif)</strong></td>
                <td style="border: none; padding: 2px 0; width: 5%;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e($security_summary['active_blocks']); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total IP Pernah Terblokir</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e($security_summary['total_blocked']); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Login Gagal (All Time)</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e($security_summary['total_failed_logins']); ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Login Gagal (7 Hari Terakhir)</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;"><?php echo e($security_summary['recent_failed_logins']); ?></td>
            </tr>
        </table>
    </div>

    
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">B. Daftar IP Tercatat</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 14%;">IP Address</th>
                <th style="width: 14%; text-align: left; padding-left: 8px;">User Terkait</th>
                <th style="width: 18%; text-align: left; padding-left: 8px;">Alasan</th>
                <th style="width: 8%;">Percobaan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 14%;">Diblokir Sampai</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="number"><?php echo e($index + 1); ?></td>
                    <td class="center" style="font-size: 8pt;"><?php echo e($client->ip_address); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e($client->user_name ?? $client->user?->name ?? '-'); ?></td>
                    <td style="text-align: left; padding-left: 8px;"><?php echo e(\Illuminate\Support\Str::limit($client->reason ?? '-', 40)); ?></td>
                    <td class="center"><?php echo e($client->attempt_count); ?></td>
                    <td class="center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->is_blocked): ?>
                            <span class="badge badge-danger">TERBLOKIR</span>
                        <?php else: ?>
                            <span class="badge badge-success">AMAN</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->blocked_until): ?>
                            <?php echo e($client->blocked_until->format('d/m/Y H:i')); ?>

                        <?php else: ?>
                            <?php echo e($client->is_blocked ? 'Permanen' : '-'); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="center">Tidak ada data IP tercatat pada periode ini.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($security_logs->count() > 0): ?>
        <div style="page-break-before: auto; margin-top: 30px;">
            <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">C. Log Keamanan Terkini</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="number">No</th>
                        <th style="width: 16%;">Tanggal</th>
                        <th style="width: 16%; text-align: left; padding-left: 8px;">User</th>
                        <th style="width: 14%;">Aksi</th>
                        <th style="width: 30%; text-align: left; padding-left: 8px;">Deskripsi</th>
                        <th style="width: 14%;">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $security_logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="number"><?php echo e($index + 1); ?></td>
                            <td class="center"><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                            <td style="text-align: left; padding-left: 8px;"><?php echo e($log->user->name ?? 'Guest'); ?></td>
                            <td class="center">
                                <?php
                                    $actionClass = match($log->action) {
                                        'LOGIN_FAILED' => 'badge-danger',
                                        'LOGIN' => 'badge-success',
                                        'LOGOUT' => 'badge-info',
                                        'PASSWORD_CHANGE' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo e($actionClass); ?>"><?php echo e($log->action); ?></span>
                            </td>
                            <td style="text-align: left; padding-left: 8px;"><?php echo e(\Illuminate\Support\Str::limit($log->description, 50)); ?></td>
                            <td class="center" style="font-size: 8pt;"><?php echo e($log->ip_address ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reports.pdf.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\reports\pdf\security.blade.php ENDPATH**/ ?>