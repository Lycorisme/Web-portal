@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN STATISTIK & REKAPITULASI</h3>
    </div>

    {{-- Metadata Laporan --}}
    <div style="margin-bottom: 20px; font-size: 10pt;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Nomor Dokumen</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">{{ $doc_number }}/{{ strtoupper(str_replace(' ', '-', $settings['site_name'] ?? 'INSTANSI')) }}/{{ \Carbon\Carbon::now()->locale('id')->isoFormat('M') }}/{{ date('Y') }}</td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Tanggal Cetak</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Petugas Penarik Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">{{ Auth::user()->name ?? 'System' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 65%; text-align: left; padding-left: 8px;">Indikator</th>
                <th style="width: 25%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: {{ str_starts_with($row[0], '  —') ? '24px' : '8px' }};">
                        @if(!str_starts_with($row[0], '  —'))
                            <strong>{{ $row[0] }}</strong>
                        @else
                            {{ $row[0] }}
                        @endif
                    </td>
                    <td class="center">{{ $row[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
