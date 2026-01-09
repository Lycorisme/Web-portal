@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA GALERI</h3>
        <p>Periode: {{ $date_from ?? '-' }} s/d {{ $date_to ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 28%;">Judul</th>
                <th style="width: 18%;">Album</th>
                <th style="width: 12%;">Tipe Media</th>
                <th style="width: 17%;">Uploader</th>
                <th style="width: 15%;">Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $gallery)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $gallery->title }}</td>
                    <td>{{ $gallery->album ?? '-' }}</td>
                    <td class="center">
                        @if($gallery->media_type === 'image')
                            <span class="badge badge-info">GAMBAR</span>
                        @elseif($gallery->media_type === 'video')
                            <span class="badge badge-warning">VIDEO</span>
                        @else
                            <span class="badge badge-secondary">{{ strtoupper($gallery->media_type) }}</span>
                        @endif
                    </td>
                    <td>{{ $gallery->uploader->name ?? '-' }}</td>
                    <td class="center">{{ $gallery->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data galeri.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Media:</strong> {{ $items->count() }}</div>
        <div class="summary-item"><strong>Gambar:</strong> {{ $items->where('media_type', 'image')->count() }}</div>
        <div class="summary-item"><strong>Video:</strong> {{ $items->where('media_type', 'video')->count() }}</div>
        <div class="summary-item"><strong>Published:</strong> {{ $items->where('is_published', true)->count() }}</div>
        <div class="summary-item"><strong>Featured:</strong> {{ $items->where('is_featured', true)->count() }}</div>
    </div>
@endsection
