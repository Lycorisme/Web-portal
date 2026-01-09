<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }} - {{ $settings['site_name'] ?? 'Portal' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; line-height: 1.4; }
        
        /* Kop Surat Styles - Diadaptasi dari Laporan Disposisi */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat table { width: 100%; border-collapse: collapse; border: none; }
        .kop-surat td { border: none; }
        .kop-surat .logo-cell { width: 100px; text-align: center; vertical-align: middle; }
        .kop-surat .text-cell { text-align: center; vertical-align: middle; }
        
        /* Menggunakan logo_url dari site_settings */
        .kop-surat img { max-height: 80px; max-width: 80px; }
        
        .kop-surat .instansi-induk { margin: 0; font-size: 12pt; text-transform: uppercase; }
        .kop-surat h2 { margin: 0; font-size: 16pt; text-transform: uppercase; font-weight: bold; }
        .kop-surat p { margin: 2px 0; font-size: 9pt; }

        .judul { text-align: center; margin-bottom: 20px; }
        .judul h3 { margin: 0; text-decoration: underline; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .judul p { margin: 5px 0; font-size: 10pt; }

        /* Table Data Styles */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .center { text-align: center; }
        .number { text-align: center; width: 30px; }

        /* Badge Styles */
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8pt;
            color: #fff;
            display: inline-block;
            text-transform: uppercase;
        }
        .badge-success { background-color: #28a745; }
        .badge-secondary { background-color: #6c757d; color: #fff; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; color: #fff; }

        /* Summary Box */
        .summary {
            margin-bottom: 30px;
            width: 300px;
        }
        .summary-item {
            font-size: 10pt;
            border-bottom: 1px dashed #ccc;
            padding: 3px 0;
        }

        /* TTD Styles - Enhanced for Mandatum Standards */
        .ttd-wrapper {
            width: 100%;
            margin-top: 30px;
        }
        .ttd-table {
            width: 100%;
            border: none;
        }
        .ttd-table td { border: none; vertical-align: top; }
        .ttd-box {
            width: 45%;
            text-align: center;
            position: relative;
        }
        .ttd-image-container {
            position: relative;
            height: 90px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 5px 0;
        }
        .ttd-image {
            height: 80px;
            z-index: 10;
            position: relative;
        }
        .ttd-stamp {
            height: 90px;
            position: absolute;
            left: 20px; /* Adjust based on visualization */
            top: 0;
            z-index: 5;
            opacity: 0.8;
            transform: rotate(-10deg); /* Slight rotation for realism */
        }
        .ttd-spacer {
            height: 90px;
        }
        .ttd-jabatan {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .tembusan {
            font-size: 8pt;
            text-align: left;
            margin-top: 50px; /* Push to bottom if needed, or align visually */
        }
        .tembusan u {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <table>
            <tr>
                <td class="logo-cell">
                    @if(!empty($settings['logo_url']))
                        {{-- Pastikan path benar sesuai filesystem Laravel --}}
                        <img src="{{ public_path($settings['logo_url']) }}" alt="Logo">
                    @endif
                </td>
                <td class="text-cell">
                    {{-- Hierarki Instansi 1 (Tingkat Atas) --}}
                    @if(!empty($settings['letterhead_parent_org_1']))
                        <div class="instansi-induk">{{ $settings['letterhead_parent_org_1'] }}</div>
                    @endif

                    {{-- Hierarki Instansi 2 (Tingkat Bawah) --}}
                    @if(!empty($settings['letterhead_parent_org_2']))
                        <div class="instansi-induk" style="font-size: 13pt; font-weight: bold;">{{ $settings['letterhead_parent_org_2'] }}</div>
                    @endif

                    {{-- Nama Organisasi Utama --}}
                    <h2>{{ $settings['letterhead_org_name'] ?? $settings['site_name'] ?? 'BTIKP PORTAL' }}</h2>

                    {{-- Alamat Lengkap --}}
                    <p>
                        {{ $settings['letterhead_street'] ?? $settings['site_address'] ?? '' }}@if(!empty($settings['letterhead_district'])), {{ $settings['letterhead_district'] }}@endif@if(!empty($settings['letterhead_city'])), {{ $settings['letterhead_city'] }}@endif@if(!empty($settings['letterhead_postal_code'])), {{ $settings['letterhead_postal_code'] }}@endif
                    </p>

                    {{-- Kontak --}}
                    <p>
                        @if(!empty($settings['letterhead_phone']))
                            Telp: {{ $settings['letterhead_phone'] }}
                        @elseif(!empty($settings['site_phone']))
                            Telp: {{ $settings['site_phone'] }}
                        @endif

                        @if(!empty($settings['letterhead_fax']))
                            | Fax: {{ $settings['letterhead_fax'] }}
                        @endif

                        @if(!empty($settings['letterhead_email']))
                            | Email: {{ $settings['letterhead_email'] }}
                        @elseif(!empty($settings['site_email']))
                            | Email: {{ $settings['site_email'] }}
                        @endif

                        @if(!empty($settings['letterhead_website']))
                            | Web: {{ $settings['letterhead_website'] }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    @yield('content')

    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                <td style="width: 55%; vertical-align: bottom;">
                    @if(!empty($settings['signature_cc']))
                        <div class="tembusan">
                            <u>Tembusan:</u>
                            <div>{!! nl2br(e($settings['signature_cc'])) !!}</div>
                        </div>
                    @endif
                </td>
                <td class="ttd-box">
                    <p>
                        {{ $settings['letterhead_city'] ?? $settings['site_city'] ?? 'Kota' }}, {{ date('d F Y') }}
                    </p>
                    <div class="ttd-jabatan">{{ $settings['leader_title'] ?? 'PIMPINAN' }}</div>
                    
                    <div class="ttd-image-container">
                        @if(!empty($settings['stamp_url']))
                             {{-- Stempel posisi absolute relative terhadap container --}}
                            <img src="{{ public_path($settings['stamp_url']) }}" class="ttd-stamp" alt="Stempel">
                        @endif

                        @if(!empty($settings['signature_url']))
                            <img src="{{ public_path($settings['signature_url']) }}" class="ttd-image" alt="TTD">
                        @else
                            <div class="ttd-spacer"></div>
                        @endif
                    </div>
                    
                    <div class="ttd-nama">
                        {{ $settings['leader_name'] ?? '(................................)' }}
                    </div>
                    @if(!empty($settings['leader_nip']))
                        <div>NIP. {{ $settings['leader_nip'] }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
