@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA KATEGORI</h3>
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
                <td style="border: none; padding: 1px;">{{ $items->count() }} kategori</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 25%; text-align: left; padding-left: 8px;">Nama Kategori</th>
                <th style="width: 20%; text-align: left; padding-left: 8px;">Slug</th>
                <th style="width: 15%;">Jumlah Artikel</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 15%;">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $category)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $category->name }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $category->slug }}</td>
                    <td class="center">{{ $category->articles_count }}</td>
                    <td class="center">
                        @if($category->is_active)
                            <span class="badge badge-success">AKTIF</span>
                        @else
                            <span class="badge badge-secondary">NONAKTIF</span>
                        @endif
                    </td>
                    <td class="center">{{ $category->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data kategori pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
