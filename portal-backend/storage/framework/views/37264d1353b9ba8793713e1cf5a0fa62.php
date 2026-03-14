<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($article->title); ?> - <?php echo e($siteName); ?></title>
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
            .hero-img { height: 200px !important; }
            .article-title { font-size: 22px !important; }
            .meta-cell { display: block !important; width: 100% !important; border-radius: 12px !important; margin-bottom: 10px !important; border: 1px solid rgba(45, 212, 191, 0.1) !important; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        
        
        
        <div class="header">
            <div style="position: relative; z-index: 1;">
                
                <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: rgba(255,255,255,0.15); border-radius: 20px; text-align: center; line-height: 80px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($logoDataUrl)): ?>
                        <img src="<?php echo e($logoDataUrl); ?>" alt="<?php echo e($siteName); ?>" style="max-width: 60px; max-height: 60px; vertical-align: middle;">
                    <?php else: ?>
                        <span style="font-size: 32px;">📰</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo e($siteName); ?></h1>
                
                
                <p style="color: rgba(255,255,255,0.9); font-size: 13px; margin-top: 12px; background: rgba(255,255,255,0.15); display: inline-block; padding: 6px 16px; border-radius: 20px;">
                    📅 <?php echo e($article->published_at ? $article->published_at->format('l, d F Y • H:i') : now()->format('l, d F Y • H:i')); ?> WIB
                </p>
            </div>
        </div>
        
        
        
        
        <div style="background: #1e293b; padding: 25px 40px; border-bottom: 1px solid rgba(45, 212, 191, 0.1);">
            <p style="color: #f1f5f9; font-size: 17px; margin: 0;">
                Halo, <strong style="color: #2dd4bf;"><?php echo e($member->name ?? 'Member'); ?></strong>! 👋
            </p>
            <p style="color: #94a3b8; font-size: 14px; margin-top: 8px;">
                Ada berita terbaru yang mungkin menarik untuk Anda.
            </p>
        </div>
        
        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($thumbnailUrl)): ?>
        <div style="position: relative; overflow: hidden;">
            <img src="<?php echo e($thumbnailUrl); ?>" alt="<?php echo e($article->title); ?>" class="hero-img" style="width: 100%; height: 280px; object-fit: cover; display: block;">
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 80%; background: linear-gradient(to top, #1e293b 0%, rgba(30, 41, 59, 0.5) 50%, transparent 100%);"></div>
            
            <div style="position: absolute; bottom: 20px; left: 20px;">
                <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; padding: 8px 18px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->categoryRelation): ?>
                        <?php echo e($article->categoryRelation->name); ?>

                    <?php else: ?>
                        Artikel
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        </div>
        <?php else: ?>
        <div style="height: 120px; background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%); display: flex; align-items: center; justify-content: center; text-align: center; border-bottom: 1px solid rgba(45, 212, 191, 0.1);">
            <div>
                <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; padding: 8px 18px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->categoryRelation): ?>
                        <?php echo e($article->categoryRelation->name); ?>

                    <?php else: ?>
                        Artikel
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        
        
        
        <div class="content" style="padding: 40px; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">
            
            
            <h2 class="article-title" style="color: #f1f5f9; font-size: 26px; font-weight: 800; line-height: 1.35; margin-bottom: 16px; letter-spacing: -0.5px;">
                <?php echo e($article->title); ?>

            </h2>
            
            
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td>
                        <span style="color: #94a3b8; font-size: 13px;">
                            ✍️ Oleh: <strong style="color: #cbd5e1;"><?php echo e($article->author ? $article->author->name : 'Admin'); ?></strong>
                        </span>
                        <span style="color: #475569; margin: 0 10px;">|</span>
                        <span style="color: #94a3b8; font-size: 13px;">
                            📂 Kategori: <strong style="color: #2dd4bf;"><?php echo e($article->categoryRelation ? $article->categoryRelation->name : 'Umum'); ?></strong>
                        </span>
                    </td>
                </tr>
            </table>
            
            
            <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%); border: 1px solid rgba(45, 212, 191, 0.2); border-radius: 16px; padding: 25px; margin-bottom: 30px; border-left: 4px solid #2dd4bf;">
                <p style="color: #cbd5e1; font-size: 15px; line-height: 1.8; margin: 0;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->excerpt): ?>
                        <?php echo e(Str::limit($article->excerpt, 250)); ?>

                    <?php elseif($article->content): ?>
                        <?php echo e(Str::limit(strip_tags($article->content), 250)); ?>

                    <?php else: ?>
                        Temukan wawasan menarik dalam artikel terbaru kami. Jangan lewatkan informasi penting yang telah kami rangkum khusus untuk Anda.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
            
            
            
            
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center">
                        <a href="<?php echo e($articleUrl); ?>" style="display: inline-block; background: linear-gradient(135deg, #0d9488 0%, #059669 100%); color: #ffffff !important; text-decoration: none; padding: 18px 50px; border-radius: 50px; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 20px rgba(13, 148, 136, 0.4);">
                            📖 Baca Selengkapnya
                        </a>
                    </td>
                </tr>
            </table>
            
            
            <div style="height: 1px; background: linear-gradient(90deg, transparent 0%, rgba(45, 212, 191, 0.3) 50%, transparent 100%); margin: 35px 0;"></div>
            
            
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="meta-cell" style="padding: 15px; background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(45, 212, 191, 0.1); border-radius: 12px 0 0 12px; border-right: none; width: 50%; vertical-align: top; text-align: center;">
                        <div style="font-size: 24px; margin-bottom: 8px;">⏱️</div>
                        <div style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Waktu Baca</div>
                        <div style="color: #f1f5f9; font-size: 16px; font-weight: 600;"><?php echo e($article->read_time ?? '5'); ?> menit</div>
                    </td>
                    <td class="meta-cell" style="padding: 15px; background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(45, 212, 191, 0.1); border-radius: 0 12px 12px 0; width: 50%; vertical-align: top; text-align: center;">
                        <div style="font-size: 24px; margin-bottom: 8px;">📊</div>
                        <div style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Dibaca</div>
                        <div style="color: #f1f5f9; font-size: 16px; font-weight: 600;"><?php echo e($article->views ?? 0); ?> kali</div>
                    </td>
                </tr>
            </table>
        </div>
        
        
        
        
        
        
        <div style="background: rgba(15, 23, 42, 0.8); padding: 25px 40px; border-top: 1px solid rgba(45, 212, 191, 0.1);">
            <div style="background: rgba(30, 41, 59, 0.5); border-radius: 12px; padding: 20px; border: 1px solid rgba(251, 191, 36, 0.15);">
                <div style="color: #fbbf24; font-size: 13px; font-weight: 600; margin-bottom: 10px;">💡 Mengapa Anda menerima email ini?</div>
                <p style="color: #94a3b8; font-size: 13px; line-height: 1.7; margin: 0;">
                    Anda menerima email ini karena Anda terdaftar sebagai member di <strong style="color: #2dd4bf;"><?php echo e($siteName); ?></strong> 
                    dan telah mengaktifkan notifikasi berita terbaru. Kami mengirimkan email ini untuk memberi tahu Anda tentang konten baru yang mungkin menarik.
                </p>
            </div>
        </div>
        
        
        <div style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, #0f172a 100%); padding: 30px 40px; text-align: center; border-top: 1px solid rgba(45, 212, 191, 0.1);">
            
            
            <div style="margin-bottom: 25px;">
                <span style="color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 15px;">Ikuti Kami</span>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <a href="<?php echo e(url('/')); ?>#facebook" style="display: inline-block; width: 40px; height: 40px; background: rgba(30, 41, 59, 0.8); border-radius: 50%; line-height: 40px; text-decoration: none; margin: 0 5px; border: 1px solid rgba(45, 212, 191, 0.2);">
                                <span style="font-size: 18px;">📘</span>
                            </a>
                            <a href="<?php echo e(url('/')); ?>#twitter" style="display: inline-block; width: 40px; height: 40px; background: rgba(30, 41, 59, 0.8); border-radius: 50%; line-height: 40px; text-decoration: none; margin: 0 5px; border: 1px solid rgba(45, 212, 191, 0.2);">
                                <span style="font-size: 18px;">🐦</span>
                            </a>
                            <a href="<?php echo e(url('/')); ?>#instagram" style="display: inline-block; width: 40px; height: 40px; background: rgba(30, 41, 59, 0.8); border-radius: 50%; line-height: 40px; text-decoration: none; margin: 0 5px; border: 1px solid rgba(45, 212, 191, 0.2);">
                                <span style="font-size: 18px;">📸</span>
                            </a>
                            <a href="<?php echo e(url('/')); ?>#youtube" style="display: inline-block; width: 40px; height: 40px; background: rgba(30, 41, 59, 0.8); border-radius: 50%; line-height: 40px; text-decoration: none; margin: 0 5px; border: 1px solid rgba(45, 212, 191, 0.2);">
                                <span style="font-size: 18px;">🎬</span>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            
            
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                <tr>
                    <td align="center">
                        <a href="<?php echo e(url('/')); ?>" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 10px;">🏠 Website</a>
                        <span style="color: #475569;">•</span>
                        <a href="<?php echo e(url('/berita')); ?>" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 10px;">📰 Semua Berita</a>
                        <span style="color: #475569;">•</span>
                        <a href="<?php echo e(url('/tentang-kami')); ?>" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 10px;">ℹ️ Tentang Kami</a>
                    </td>
                </tr>
            </table>
            
            
            <div style="height: 1px; background: linear-gradient(90deg, transparent 0%, rgba(71, 85, 105, 0.3) 50%, transparent 100%); margin: 20px 0;"></div>
            
            
            <div style="margin-bottom: 20px;">
                <a href="<?php echo e(url('/unsubscribe?email=' . urlencode($member->email ?? ''))); ?>" style="color: #ef4444; text-decoration: none; font-size: 13px; padding: 10px 25px; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 25px; display: inline-block;">
                    🔕 Berhenti Berlangganan
                </a>
                <span style="color: #475569; margin: 0 8px;">|</span>
                <a href="<?php echo e(url('/member/preferences')); ?>" style="color: #94a3b8; text-decoration: none; font-size: 13px;">
                    ⚙️ Atur Preferensi Email
                </a>
            </div>
            
            
            <p style="color: #64748b; font-size: 12px; margin-bottom: 8px;">
                Email ini dikirim secara otomatis oleh <strong style="color: #94a3b8;"><?php echo e($siteName); ?></strong>.
            </p>
            <p style="color: #64748b; font-size: 11px; margin-bottom: 15px;">
                📍 <?php echo e($siteName); ?> • Jl. Contoh Alamat No. 123, Kota, Indonesia
            </p>
            
            <div style="color: #475569; font-size: 11px; padding-top: 15px; border-top: 1px solid rgba(71, 85, 105, 0.3);">
                © <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\web-portal\portal-backend\resources\views\emails\new-article.blade.php ENDPATH**/ ?>