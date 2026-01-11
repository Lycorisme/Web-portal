<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - {{ $siteName }}</title>
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
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #0f172a;
            color: #e2e8f0;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #2dd4bf 100%);
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }
        .header-content {
            position: relative;
            z-index: 1;
        }
        .logo-container {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .logo-container img {
            max-width: 60px;
            max-height: 60px;
        }
        .site-name {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .tagline {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            margin-top: 8px;
            font-weight: 400;
        }
        
        /* Success Badge */
        .success-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            display: inline-block;
            margin: 30px 0;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        /* Content */
        .content {
            padding: 40px;
        }
        .section-title {
            color: #2dd4bf;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .message-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
            border: 1px solid rgba(45, 212, 191, 0.2);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
        }
        .message-text {
            color: #cbd5e1;
            font-size: 16px;
            line-height: 1.8;
        }
        .highlight {
            color: #2dd4bf;
            font-weight: 600;
        }
        
        /* Info Grid */
        .info-grid {
            display: table;
            width: 100%;
            margin-top: 25px;
        }
        .info-row {
            display: table-row;
        }
        .info-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(45, 212, 191, 0.1);
            vertical-align: top;
        }
        .info-item:first-child {
            border-radius: 12px 0 0 12px;
            border-right: none;
        }
        .info-item:last-child {
            border-radius: 0 12px 12px 0;
        }
        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(45, 212, 191, 0.2) 0%, rgba(20, 184, 166, 0.1) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 20px;
        }
        .info-label {
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .info-value {
            color: #f1f5f9;
            font-size: 14px;
            font-weight: 600;
        }
        
        /* Status Indicator */
        .status-indicator {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.1) 100%);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 16px;
            padding: 25px 30px;
            margin-top: 25px;
            text-align: center;
        }
        .status-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 28px;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }
        .status-title {
            color: #10b981;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .status-message {
            color: #94a3b8;
            font-size: 14px;
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(45, 212, 191, 0.3) 50%, transparent 100%);
            margin: 30px 0;
        }
        
        /* Tips Section */
        .tips-section {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 16px;
            padding: 25px;
            margin-top: 25px;
        }
        .tips-title {
            color: #fbbf24;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .tips-list li {
            color: #94a3b8;
            font-size: 13px;
            padding: 8px 0;
            padding-left: 24px;
            position: relative;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }
        .tips-list li:last-child {
            border-bottom: none;
        }
        .tips-list li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, #0f172a 100%);
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid rgba(45, 212, 191, 0.1);
        }
        .footer-logo {
            margin-bottom: 15px;
        }
        .footer-text {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 8px;
        }
        .footer-links {
            margin-top: 15px;
        }
        .footer-link {
            color: #0d9488;
            text-decoration: none;
            font-size: 12px;
            margin: 0 10px;
        }
        .footer-link:hover {
            color: #2dd4bf;
        }
        .copyright {
            color: #475569;
            font-size: 11px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(71, 85, 105, 0.3);
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 25px 20px;
            }
            .site-name {
                font-size: 22px;
            }
            .message-card {
                padding: 20px;
            }
            .info-item {
                display: block;
                width: 100%;
                border-radius: 12px !important;
                margin-bottom: 10px;
                border: 1px solid rgba(45, 212, 191, 0.1) !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="logo-container" style="width: 80px; height: 80px; margin: 0 auto 20px; background: rgba(255,255,255,0.15); border-radius: 20px; text-align: center; line-height: 80px;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="{{ $siteName }}" style="max-width: 60px; max-height: 60px; vertical-align: middle;">
                    @else
                        <span style="font-size: 32px;">📧</span>
                    @endif
                </div>
                <h1 class="site-name">{{ $siteName }}</h1>
                <p class="tagline">Sistem Portal Terintegrasi</p>
            </div>
        </div>
        
        <!-- Success Badge -->
        <div style="text-align: center; background: #1e293b; padding: 10px 0;">
            <div class="success-badge" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 30px; border-radius: 50px; display: inline-block; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                ✉️ Test Email Berhasil Dikirim
            </div>
        </div>
        
        <!-- Content -->
        <div class="content" style="padding: 40px; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">
            <!-- Main Message -->
            <div class="message-card" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%); border: 1px solid rgba(45, 212, 191, 0.2); border-radius: 16px; padding: 30px;">
                <p class="message-text" style="color: #cbd5e1; font-size: 16px; line-height: 1.8; margin: 0;">
                    Selamat! 🎉 Email ini menandakan bahwa konfigurasi <span class="highlight" style="color: #2dd4bf; font-weight: 600;">SMTP</span> pada 
                    <span class="highlight" style="color: #2dd4bf; font-weight: 600;">{{ $siteName }}</span> telah berhasil dikonfigurasi dengan benar.
                </p>
            </div>
            
            <!-- Status Indicator -->
            <div class="status-indicator" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.1) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 25px 30px; text-align: center;">
                <div class="status-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: inline-block; text-align: center; line-height: 60px; font-size: 28px; margin-bottom: 15px;">
                    ✅
                </div>
                <h3 class="status-title" style="color: #10b981; font-size: 18px; font-weight: 700; margin: 0 0 8px 0;">Konfigurasi SMTP Valid</h3>
                <p class="status-message" style="color: #94a3b8; font-size: 14px; margin: 0;">Server email Anda siap digunakan untuk mengirim notifikasi dan reset password.</p>
            </div>
            
            <!-- Info Grid -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px;">
                <tr>
                    <td style="padding: 15px; background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(45, 212, 191, 0.1); border-radius: 12px 0 0 12px; border-right: none; width: 50%; vertical-align: top;">
                        <div style="font-size: 20px; margin-bottom: 12px;">📅</div>
                        <div class="info-label" style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Waktu Pengiriman</div>
                        <div class="info-value" style="color: #f1f5f9; font-size: 14px; font-weight: 600;">{{ $sentAt }}</div>
                    </td>
                    <td style="padding: 15px; background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(45, 212, 191, 0.1); border-radius: 0 12px 12px 0; width: 50%; vertical-align: top;">
                        <div style="font-size: 20px; margin-bottom: 12px;">🖥️</div>
                        <div class="info-label" style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">IP Server</div>
                        <div class="info-value" style="color: #f1f5f9; font-size: 14px; font-weight: 600;">{{ $serverIp }}</div>
                    </td>
                </tr>
            </table>
            
            <!-- Divider -->
            <div class="divider" style="height: 1px; background: linear-gradient(90deg, transparent 0%, rgba(45, 212, 191, 0.3) 50%, transparent 100%); margin: 30px 0;"></div>
            
            <!-- Tips Section -->
            <div class="tips-section" style="background: rgba(30, 41, 59, 0.5); border-radius: 16px; padding: 25px; border: 1px solid rgba(251, 191, 36, 0.2);">
                <div class="tips-title" style="color: #fbbf24; font-size: 14px; font-weight: 600; margin-bottom: 15px;">
                    💡 Tips Penggunaan Email
                </div>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="color: #94a3b8; font-size: 13px; padding: 8px 0; padding-left: 24px; border-bottom: 1px solid rgba(148, 163, 184, 0.1);">
                            <span style="color: #10b981; font-weight: bold; margin-right: 8px;">✓</span>
                            Pastikan email tidak masuk ke folder Spam
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #94a3b8; font-size: 13px; padding: 8px 0; padding-left: 24px; border-bottom: 1px solid rgba(148, 163, 184, 0.1);">
                            <span style="color: #10b981; font-weight: bold; margin-right: 8px;">✓</span>
                            Simpan konfigurasi SMTP dengan aman
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #94a3b8; font-size: 13px; padding: 8px 0; padding-left: 24px; border-bottom: 1px solid rgba(148, 163, 184, 0.1);">
                            <span style="color: #10b981; font-weight: bold; margin-right: 8px;">✓</span>
                            Gunakan App Password jika menggunakan Gmail
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #94a3b8; font-size: 13px; padding: 8px 0; padding-left: 24px;">
                            <span style="color: #10b981; font-weight: bold; margin-right: 8px;">✓</span>
                            Periksa kuota pengiriman email secara berkala
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer" style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, #0f172a 100%); padding: 30px 40px; text-align: center; border-top: 1px solid rgba(45, 212, 191, 0.1);">
            <p class="footer-text" style="color: #64748b; font-size: 12px; margin-bottom: 8px;">
                Email ini dikirim secara otomatis oleh sistem <strong style="color: #94a3b8;">{{ $siteName }}</strong>.
            </p>
            <p class="footer-text" style="color: #64748b; font-size: 12px; margin-bottom: 8px;">
                Ini adalah email test untuk verifikasi konfigurasi SMTP.
            </p>
            <div class="copyright" style="color: #475569; font-size: 11px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(71, 85, 105, 0.3);">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
