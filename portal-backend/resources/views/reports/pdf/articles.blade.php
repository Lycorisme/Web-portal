@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA BERITA</h3>
    </div>

    {{-- Metadata Laporan --}}
    <div style="margin-bottom: 20px; font-size: 10pt;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 18%; border: none; padding: 1px;"><strong>Nomor Dokumen</strong></td>
                <td style="width: 2%; border: none; padding: 1px;">:</td>
                <td style="border: none; padding: 1px;">005/{{ strtoupper(str_replace(' ', '-', $settings['site_name'] ?? 'INSTANSI')) }}/I/{{ date('Y') }}</td> 
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
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 35%; text-align: left; padding-left: 8px;">Judul</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%; text-align: left; padding-left: 8px;">Penulis</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 15%;">Tanggal Publish</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $article)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $article->title }}</td>
                    <td class="center">{{ $article->categoryRelation->name ?? '-' }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $article->author->name ?? '-' }}</td>
                    <td class="center">
                        @php
                            $statusClass = match($article->status) {
                                'published' => 'badge-success',
                                'draft' => 'badge-secondary',
                                'pending' => 'badge-warning',
                                'rejected' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ strtoupper($article->status) }}</span>
                    </td>
                    <td class="center">
                        {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data berita pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
