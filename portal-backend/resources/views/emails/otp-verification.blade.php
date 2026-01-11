<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f4f7fa;
            color: #333;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%);
            padding: 30px 40px;
            text-align: center;
        }
        .header img {
            max-height: 60px;
            width: auto;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin-top: 15px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            color: #1e3a5f;
            margin-bottom: 20px;
        }
        .message {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
        }
        .otp-container {
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            border: 2px dashed #cbd5e1;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 12px;
            color: #0d9488;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
        }
        .otp-expiry {
            font-size: 13px;
            color: #ef4444;
            margin-top: 10px;
        }
        .otp-expiry strong {
            color: #dc2626;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-box p {
            font-size: 13px;
            color: #92400e;
            margin: 0;
        }
        .security-notice {
            background-color: #fef2f2;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .security-notice h3 {
            font-size: 14px;
            color: #dc2626;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .security-notice ul {
            font-size: 13px;
            color: #7f1d1d;
            margin-left: 20px;
        }
        .security-notice li {
            margin-bottom: 5px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 25px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 5px;
        }
        .footer a {
            color: #0d9488;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 25px;
            }
            .otp-code {
                font-size: 32px;
                letter-spacing: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            @if($logoUrl)
                <img src="{{ url($logoUrl) }}" alt="{{ $siteName }}">
            @endif
            <h1>{{ $siteName }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Halo, <strong>{{ $userName }}</strong></p>
            
            @if($type === 'password_reset')
                <p class="message">
                    Kami menerima permintaan untuk mereset password akun Anda. 
                    Gunakan kode OTP di bawah ini untuk melanjutkan proses reset password.
                </p>
            @elseif($type === 'login_2fa')
                <p class="message">
                    Untuk menyelesaikan proses login, masukkan kode verifikasi berikut 
                    di halaman login.
                </p>
            @else
                <p class="message">
                    Gunakan kode OTP di bawah ini untuk memverifikasi aksi Anda.
                </p>
            @endif

            <!-- OTP Code Box -->
            <div class="otp-container">
                <p class="otp-label">Kode Verifikasi Anda</p>
                <p class="otp-code">{{ $otpCode }}</p>
                <p class="otp-expiry">
                    ⏱️ Kode berlaku selama <strong>{{ $expiryMinutes }} menit</strong>
                </p>
            </div>

            <div class="info-box">
                <p>
                    ⚠️ Jangan bagikan kode ini kepada siapapun termasuk pihak yang mengaku 
                    dari {{ $siteName }}. Kami tidak pernah meminta kode OTP melalui telepon atau chat.
                </p>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <h3>🔒 Keamanan Akun Anda</h3>
                <ul>
                    <li>Jika Anda tidak melakukan permintaan ini, abaikan email ini.</li>
                    <li>Password Anda tetap aman dan tidak akan berubah.</li>
                    <li>Segera hubungi admin jika ada aktivitas mencurigakan.</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem {{ $siteName }}.</p>
            <p>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
