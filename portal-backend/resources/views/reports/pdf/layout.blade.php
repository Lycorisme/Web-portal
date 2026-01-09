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

        /* TTD Styles - Diadaptasi dari Laporan Disposisi */
        .ttd-wrapper {
            width: 100%;
            margin-top: 20px;
        }
        .ttd-table {
            width: 100%;
            border: none;
        }
        .ttd-table td { border: none; }
        .ttd-box {
            width: 40%;
            text-align: center;
        }
        .ttd-image {
            height: 70px;
            margin: 5px auto;
            display: block;
        }
        .ttd-spacer {
            height: 70px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
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
                    @if(!empty($settings['parent_organization']))
                        <div class="instansi-induk">{{ $settings['parent_organization'] }}</div>
                    @endif
                    <h2>{{ $settings['site_name'] ?? 'BTIKP PORTAL' }}</h2>
                    <p>{{ $settings['site_address'] ?? '' }}</p>
                    <p>
                        @if(!empty($settings['site_phone'])) Telp: {{ $settings['site_phone'] }} @endif
                        @if(!empty($settings['site_email'])) | Email: {{ $settings['site_email'] }} @endif
                        @if(!empty($settings['site_website'])) | Web: {{ $settings['site_website'] }} @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    @yield('content')

    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                <td style="width: 60%;"></td>
                <td class="ttd-box">
                    <p>
                        {{ $settings['site_city'] ?? 'Kota' }}, {{ date('d F Y') }}
                    </p>
                    <p>{{ $settings['leader_title'] ?? 'Pimpinan' }}</p> 
                    
                    @if(!empty($settings['signature_url']))
                        <img src="{{ public_path($settings['signature_url']) }}" class="ttd-image" alt="TTD">
                    @else
                        <div class="ttd-spacer"></div>
                    @endif
                    
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
