@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA ARTIKEL</h3>
        <p>Periode: {{ $date_from ?? '-' }} s/d {{ $date_to ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 35%;">Judul</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Penulis</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 15%;">Tanggal Publish</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $article)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->categoryRelation->name ?? '-' }}</td>
                    <td>{{ $article->author->name ?? '-' }}</td>
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
                    <td colspan="6" class="center">Tidak ada data artikel pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Artikel:</strong> {{ $items->count() }}</div>
        <div class="summary-item"><strong>Published:</strong> {{ $items->where('status', 'published')->count() }}</div>
        <div class="summary-item"><strong>Pending:</strong> {{ $items->where('status', 'pending')->count() }}</div>
    </div>
@endsection
