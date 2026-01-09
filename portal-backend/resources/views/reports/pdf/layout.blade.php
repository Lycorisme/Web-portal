<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #000000;
            background-color: #ffffff;
        }

        /* Page Layout */
        @page {
            margin: 20mm 15mm 25mm 15mm;
        }

        /* Header Styles */
        .header {
            border-bottom: 3px double #000000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .header-logo img {
            max-width: 70px;
            max-height: 70px;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .header-address {
            font-size: 10px;
            color: #333333;
        }

        /* Letterhead Image */
        .letterhead {
            width: 100%;
            margin-bottom: 20px;
        }

        .letterhead img {
            width: 100%;
            max-height: 100px;
            object-fit: contain;
        }

        /* Document Title */
        .document-title {
            text-align: center;
            margin: 25px 0;
        }

        .document-title h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .document-title .period {
            font-size: 11px;
            color: #333333;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f5f5f5;
            border: 1px solid #000000;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .data-table td {
            border: 1px solid #000000;
            padding: 6px;
            font-size: 10px;
            vertical-align: top;
        }

        .data-table th.center,
        .data-table td.center {
            text-align: center;
        }

        .data-table th.number,
        .data-table td.number {
            text-align: center;
            width: 40px;
        }

        .data-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Summary Section */
        .summary {
            margin-top: 15px;
            font-size: 11px;
        }

        .summary-item {
            margin-bottom: 5px;
        }

        /* Footer Styles */
        .footer {
            position: fixed;
            bottom: -20mm;
            left: 0;
            right: 0;
            height: 20mm;
            font-size: 9px;
            color: #666666;
            border-top: 1px solid #cccccc;
            padding-top: 5px;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
            text-align: left;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-date {
            margin-bottom: 5px;
            font-size: 10px;
        }

        .signature-space {
            height: 60px;
            position: relative;
        }

        .signature-space img {
            max-width: 100px;
            max-height: 50px;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .stamp-space img {
            max-width: 80px;
            max-height: 80px;
            position: absolute;
            right: 10px;
            top: 0;
            opacity: 0.8;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10px;
            border-top: 1px solid #000000;
            padding-top: 5px;
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            border-radius: 3px;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>
<body>
    {{-- Header with Letterhead or Logo --}}
    @if(!empty($settings['letterhead_url']))
        <div class="letterhead">
            <img src="{{ $settings['letterhead_url'] }}" alt="Kop Surat">
        </div>
    @else
        <div class="header">
            <div class="header-content">
                @if(!empty($settings['logo_url']))
                    <div class="header-logo">
                        <img src="{{ $settings['logo_url'] }}" alt="Logo">
                    </div>
                @endif
                <div class="header-text">
                    <div class="header-title">{{ $settings['site_name'] ?? 'Portal Admin' }}</div>
                    <div class="header-address">
                        @if(!empty($settings['site_address']))
                            {{ $settings['site_address'] }}<br>
                        @endif
                        @if(!empty($settings['site_phone']) || !empty($settings['site_email']))
                            @if(!empty($settings['site_phone']))
                                Telp: {{ $settings['site_phone'] }}
                            @endif
                            @if(!empty($settings['site_phone']) && !empty($settings['site_email']))
                                |
                            @endif
                            @if(!empty($settings['site_email']))
                                Email: {{ $settings['site_email'] }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Document Title --}}
    <div class="document-title">
        <h1>{{ $title ?? 'Laporan' }}</h1>
        @if(isset($period))
            <p class="period">Periode: {{ $period }}</p>
        @endif
    </div>

    {{-- Content --}}
    @yield('content')

    {{-- Signature Section --}}
    @if(!empty($settings['signature_url']) || !empty($settings['printed_by']))
        <div class="signature-section clearfix">
            <div class="signature-box">
                <div class="signature-date">{{ $settings['printed_at'] ?? now()->format('d F Y') }}</div>
                <div class="signature-space">
                    @if(!empty($settings['signature_url']))
                        <img src="{{ $settings['signature_url'] }}" alt="Tanda Tangan">
                    @endif
                    @if(!empty($settings['stamp_url']))
                        <span class="stamp-space">
                            <img src="{{ $settings['stamp_url'] }}" alt="Stempel">
                        </span>
                    @endif
                </div>
                <div class="signature-name">{{ $settings['printed_by'] ?? 'Administrator' }}</div>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                Dicetak pada: {{ $settings['printed_at'] ?? now()->format('d F Y, H:i') }}
            </div>
            <div class="footer-right">
                {{ $settings['site_name'] ?? 'Portal Admin' }}
            </div>
        </div>
    </div>
</body>
</html>
