@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA GALLERY / MEDIA</h3>
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
                <td style="width: 18%; border: none; padding: 1px;"><strong>Periode Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">
                    @if($has_date_filter ?? false)
                        {{ $date_from ?: '-' }} s/d {{ $date_to ?: '-' }}
                    @else
                        Semua Data
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Petugas Penarik Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">{{ Auth::user()->name ?? 'System' }}</td>
            </tr>
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Jumlah Data</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">{{ $items->count() }} media</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 25%; text-align: left; padding-left: 8px;">Judul</th>
                <th style="width: 15%;">Album</th>
                <th style="width: 10%;">Tipe Media</th>
                <th style="width: 15%; text-align: left; padding-left: 8px;">Uploader</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 14%;">Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $gallery)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $gallery->title ?? '-' }}</td>
                    <td class="center">{{ $gallery->album ?? '-' }}</td>
                    <td class="center">
                        <span class="badge {{ $gallery->media_type === 'image' ? 'badge-info' : 'badge-warning' }}">
                            {{ strtoupper($gallery->media_type) }}
                        </span>
                    </td>
                    <td style="text-align: left; padding-left: 8px;">{{ $gallery->uploader->name ?? '-' }}</td>
                    <td class="center">
                        @if($gallery->is_published)
                            <span class="badge badge-success">PUBLISH</span>
                        @else
                            <span class="badge badge-secondary">DRAFT</span>
                        @endif
                    </td>
                    <td class="center">{{ $gallery->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data gallery pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
