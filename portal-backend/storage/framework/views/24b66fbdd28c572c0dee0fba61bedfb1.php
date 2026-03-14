<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - <?php echo e($siteName); ?></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; background-color: #0f172a; color: #e2e8f0; -webkit-font-smoothing: antialiased; }
        .email-container { max-width: 600px; margin: 0 auto; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .header { background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #2dd4bf 100%); padding: 40px; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%); }
        @media only screen and (max-width: 600px) {
            .header { padding: 30px 20px; }
            .content { padding: 25px 20px !important; }
            .info-cell { display: block !important; width: 100% !important; border-radius: 12px !important; margin-bottom: 10px !important; border: 1px solid rgba(45, 212, 191, 0.1) !important; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        
        <div class="header">
            <div style="position: relative; z-index: 1;">
                <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: rgba(255,255,255,0.15); border-radius: 20px; text-align: center; line-height: 80px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoBase64): ?>
                        <img src="<?php echo e($logoBase64); ?>" alt="<?php echo e($siteName); ?>" style="max-width: 60px; max-height: 60px; vertical-align: middle;">
                    <?php else: ?>
                        <span style="font-size: 32px;">📧</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo e($siteName); ?></h1>
                <p style="color: rgba(255,255,255,0.9); font-size: 14px; margin-top: 8px;">Sistem Portal Terintegrasi</p>
            </div>
        </div>
        
        
        <div style="text-align: center; background: #1e293b; padding: 10px 0;">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 30px; border-radius: 50px; display: inline-block; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                ✉️ Test Email Berhasil Dikirim
            </div>
        </div>
        
        
        <div class="content" style="padding: 40px; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">
            
            <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%); border: 1px solid rgba(45, 212, 191, 0.2); border-radius: 16px; padding: 30px; margin-bottom: 25px;">
                <p style="color: #cbd5e1; font-size: 16px; line-height: 1.8; margin: 0;">
                    Selamat! 🎉 Email ini menandakan bahwa konfigurasi <span style="color: #2dd4bf; font-weight: 600;">SMTP</span> pada 
                    <span style="color: #2dd4bf; font-weight: 600;"><?php echo e($siteName); ?></span> telah berhasil dikonfigurasi dengan benar.
                </p>
            </div>
            
            
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.1) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 25px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: inline-block; text-align: center; line-height: 60px; font-size: 28px; margin-bottom: 15px; box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);">✅</div>
                <h3 style="color: #10b981; font-size: 18px; font-weight: 700; margin: 0 0 8px 0;">Konfigurasi SMTP Valid</h3>
                <p style="color: #94a3b8; font-size: 14px; margin: 0;">Server email Anda siap digunakan untuk mengirim notifikasi dan reset password.</p>
            </div>
            
            
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px;">
                <tr>
                    <td class="info-cell" style="padding: 15px; background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(45, 212, 191, 0.1); border-radius: 12px 0 0 12px; border-right: none; width: 50%; vertical-align: top;">
                        <div style="font-size: 20px; margin-bottom: 12px;">📅</div>
                        <div style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Waktu Pengiriman</div>
                        <div style="color: #f1f5f9; font-size: 14px; font-weight: 600;"><?php echo e($sentAt); ?></div>
                    </td>
                    <td class="info-cell" style="padding: 15px; background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(45, 212, 191, 0.1); border-radius: 0 12px 12px 0; width: 50%; vertical-align: top;">
                        <div style="font-size: 20px; margin-bottom: 12px;">🖥️</div>
                        <div style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">IP Server</div>
                        <div style="color: #f1f5f9; font-size: 14px; font-weight: 600;"><?php echo e($serverIp); ?></div>
                    </td>
                </tr>
            </table>
            
            
            <div style="height: 1px; background: linear-gradient(90deg, transparent 0%, rgba(45, 212, 191, 0.3) 50%, transparent 100%); margin: 30px 0;"></div>
            
            
            <div style="background: rgba(30, 41, 59, 0.5); border-radius: 16px; padding: 25px; border: 1px solid rgba(251, 191, 36, 0.2);">
                <div style="color: #fbbf24; font-size: 14px; font-weight: 600; margin-bottom: 15px;">💡 Tips Penggunaan Email</div>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Pastikan email tidak masuk ke folder Spam', 'Simpan konfigurasi SMTP dengan aman', 'Gunakan App Password jika menggunakan Gmail', 'Periksa kuota pengiriman email secara berkala']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="color: #94a3b8; font-size: 13px; padding: 8px 0; padding-left: 24px; <?php echo e($index < 3 ? 'border-bottom: 1px solid rgba(148, 163, 184, 0.1);' : ''); ?>">
                                <span style="color: #10b981; font-weight: bold; margin-right: 8px;">✓</span><?php echo e($tip); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </table>
            </div>
        </div>
        
        
        <div style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, #0f172a 100%); padding: 30px 40px; text-align: center; border-top: 1px solid rgba(45, 212, 191, 0.1);">
            <p style="color: #64748b; font-size: 12px; margin-bottom: 8px;">
                Email ini dikirim secara otomatis oleh sistem <strong style="color: #94a3b8;"><?php echo e($siteName); ?></strong>.
            </p>
            <p style="color: #64748b; font-size: 12px; margin-bottom: 8px;">
                Ini adalah email test untuk verifikasi konfigurasi SMTP.
            </p>
            <div style="color: #475569; font-size: 11px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(71, 85, 105, 0.3);">
                © <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\emails\test-email.blade.php ENDPATH**/ ?>