@extends('reports.pdf.layout')

@section('content')
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
                        @switch($article->status)
                            @case('published')
                                <span class="badge badge-success">Published</span>
                                @break
                            @case('draft')
                                <span class="badge badge-secondary">Draft</span>
                                @break
                            @case('pending')
                                <span class="badge badge-warning">Pending</span>
                                @break
                            @case('rejected')
                                <span class="badge badge-danger">Rejected</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ $article->status }}</span>
                        @endswitch
                    </td>
                    <td class="center">
                        {{ $article->published_at ? $article->published_at->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data artikel.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Artikel:</strong> {{ $items->count() }}</div>
        <div class="summary-item"><strong>Published:</strong> {{ $items->where('status', 'published')->count() }}</div>
        <div class="summary-item"><strong>Draft:</strong> {{ $items->where('status', 'draft')->count() }}</div>
        <div class="summary-item"><strong>Pending:</strong> {{ $items->where('status', 'pending')->count() }}</div>
    </div>
@endsection
