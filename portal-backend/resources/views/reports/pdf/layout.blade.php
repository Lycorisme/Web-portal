<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 10pt; 
            color: #1F2937; 
            line-height: 1.3; 
            background-color: #FFFFFF;
        }
        
        /* Kop Surat Styles - Standar Dinas */
        .kop-surat {
            text-align: center;
            padding-bottom: 2px;
            margin-bottom: 20px;
            position: relative;
        }
        .kop-surat table { width: 100%; border-collapse: collapse; border: none; }
        .kop-surat td { border: none; }
        .kop-surat .logo-cell { width: 90px; text-align: center; vertical-align: middle; padding-right: 15px; }
        .kop-surat .text-cell { text-align: center; vertical-align: middle; }
        
        .kop-surat img { width: 80px; height: auto; }
        
        /* Hierarki Teks Kop Surat */
        /* Hierarki Teks Kop Surat */
        .kop-row-1 { 
            font-family: 'Times New Roman', Times, serif;
            font-size: 14pt; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            line-height: 1.1;
            color: #000;
            margin-bottom: 2px;
        }
        .kop-row-main { 
            font-family: 'Times New Roman', Times, serif;
            font-size: 17pt; 
            font-weight: 900; /* Extra Bold */
            text-transform: uppercase; 
            letter-spacing: 1px;
            margin-top: 5px;
            margin-bottom: 5px;
            line-height: 1;
            color: #000;
        }
        
        .kop-address { 
            margin-top: 5px; 
            font-size: 10pt; 
            font-weight: normal; 
            line-height: 1.2;
        }
        
        /* Garis Penutup Ganda (Tebal - Tipis) */
        .kop-separator {
            margin-top: 10px;
            border-top: 4px solid #000; /* Outer Thick */
            border-bottom: 1px solid #000; /* Inner Thin */
            height: 2px; /* Gap between */
            width: 100%;
        }

        .judul { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .judul h3 { margin: 0; font-size: 12pt; font-weight: bold; }
        .judul p { margin: 2px 0; font-size: 10pt; }

        /* Table Data Styles */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px 6px; /* Padding 2mm-3mm approx */
            vertical-align: top;
            font-size: 9pt;
        }
        table.data-table th {
            background-color: #F3F4F6; /* Abu-abu muda */
            color: #000;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .center { text-align: center; }
        .number { text-align: center; width: 30px; }

        /* Badge Styles - Pastel Soft Colors */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            border: 1px solid transparent;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; } /* Emerald-100 */
        .badge-secondary { background-color: #f3f4f6; color: #1f2937; border-color: #e5e7eb; } /* Gray-100 */
        .badge-warning { background-color: #fef3c7; color: #92400e; border-color: #fde68a; } /* Amber-100 */
        .badge-danger { background-color: #fee2e2; color: #b91c1c; border-color: #fecaca; } /* Red-100 */
        .badge-info { background-color: #dbeafe; color: #1e40af; border-color: #bfdbfe; } /* Blue-100 */

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

        /* TTD Styles - Kanan Bawah */
        .ttd-wrapper {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .ttd-table {
            width: 100%;
            border: none;
        }
        .ttd-table td { border: none; vertical-align: top; }
        
        .ttd-box {
            width: 40%;
            text-align: center; /* Center content inside the box */
            /* align-self: flex-end; -> Not supported in tables/PDF well */
            float: right; /* Force right */
        }
        
        .ttd-image-container {
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 5px 0;
            position: relative;
        }
        .ttd-image {
            height: 80px;
            z-index: 10;
        }
        .ttd-stamp {
            height: 85px;
            position: absolute;
            left: 10%; 
            top: 0;
            z-index: 5;
            opacity: 0.9;
            transform: rotate(-10deg); 
        }
        .ttd-spacer { height: 90px; }
        
        .ttd-jabatan {
            font-weight: normal; /* Jabatan usually normal or bold, user said 'Row 1 Jabatan' */
            font-size: 10pt;
            margin-bottom: 5px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
            margin-top: 5px;
        }
        .ttd-nip {
            font-size: 10pt;
        }
        
        .tembusan {
            font-size: 8pt;
            text-align: left;
            margin-top: 20px;
            color: #4b5563;
        }
        .tembusan u { font-weight: bold; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <table>
            <tr>
                <td class="logo-cell">
                    @if(!empty($settings['logo_url']))
                        <img src="{{ public_path($settings['logo_url']) }}" alt="Logo">
                    @endif
                </td>
                <td class="text-cell">
                    {{-- Baris 1: Instansi Induk (12pt Bold) --}}
                    @if(!empty($settings['letterhead_parent_org_1']))
                        <div class="kop-row-1">{{ $settings['letterhead_parent_org_1'] }}</div>
                    @endif

                    @if(!empty($settings['letterhead_parent_org_2']))
                        <div class="kop-row-1">{{ $settings['letterhead_parent_org_2'] }}</div>
                    @endif

                    @if(!empty($settings['letterhead_org_name']))
                        <div class="kop-row-main">{{ $settings['letterhead_org_name'] }}</div>
                    @endif

                    {{-- Alamat dan Kontak --}}
                    <div class="kop-address">
                        {{ $settings['letterhead_street'] ?? $settings['site_address'] ?? '' }}
                        
                        @if(!empty($settings['letterhead_district'])) {{ $settings['letterhead_district'] }}, @endif
                        @if(!empty($settings['letterhead_city'])) {{ $settings['letterhead_city'] }} @endif
                        @if(!empty($settings['letterhead_postal_code'])) {{ $settings['letterhead_postal_code'] }} @endif

                        <br>
                        {{-- Kontak dengan separator pipe --}}
                        @php $contacts = []; @endphp
                        @if(!empty($settings['letterhead_phone'])) @php $contacts[] = 'Telp: ' . $settings['letterhead_phone']; @endphp @endif
                        @if(!empty($settings['letterhead_fax'])) @php $contacts[] = 'Fax: ' . $settings['letterhead_fax']; @endphp @endif
                        @if(!empty($settings['letterhead_email'])) @php $contacts[] = 'Email: ' . $settings['letterhead_email']; @endphp @endif
                        @if(!empty($settings['letterhead_website'])) @php $contacts[] = 'Web: ' . $settings['letterhead_website']; @endphp @endif
                        
                        {!! implode(' | ', $contacts) !!}
                    </div>
                </td>
            </tr>
        </table>
        <div class="kop-separator"></div>
    </div>

    @yield('content')

    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                <td style="width: 50%; vertical-align: bottom;">
                    @if(!empty($settings['signature_cc']))
                        <div class="tembusan">
                            <u>Tembusan:</u>
                            <div>{!! nl2br(e($settings['signature_cc'])) !!}</div>
                        </div>
                    @endif
                </td>
                <td class="ttd-box">
                    <p style="margin-bottom: 5px;">
                        {{ $settings['letterhead_city'] ?? $settings['site_city'] ?? 'Kota' }}, {{ date('d F Y') }}
                    </p>
                    <div class="ttd-jabatan">{{ $settings['leader_title'] ?? 'Kepala Balai' }}</div>
                    
                    <div class="ttd-image-container">
                        @if(!empty($settings['stamp_url']))
                            <img src="{{ public_path($settings['stamp_url']) }}" class="ttd-stamp" alt="Stempel">
                        @endif

                        @if(!empty($settings['signature_url']))
                            <img src="{{ public_path($settings['signature_url']) }}" class="ttd-image" alt="TTD">
                        @else
                            <div class="ttd-spacer"></div>
                        @endif
                    </div>
                    
                    <div class="ttd-nama">
                        {{ $settings['leader_name'] ?? '(Nama Lengkap)' }}
                    </div>
                    @if(!empty($settings['leader_nip']))
                        <div class="ttd-nip">NIP. {{ str_replace(' ', '', $settings['leader_nip']) }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
