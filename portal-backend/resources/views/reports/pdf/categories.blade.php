@extends('reports.pdf.layout')

@section('content')
    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 25%;">Nama Kategori</th>
                <th style="width: 20%;">Slug</th>
                <th style="width: 15%;">Jumlah Artikel</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 18%;">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $category)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td class="center">{{ $category->articles_count ?? 0 }}</td>
                    <td class="center">
                        @if($category->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="center">{{ $category->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Kategori:</strong> {{ $items->count() }}</div>
        <div class="summary-item"><strong>Aktif:</strong> {{ $items->where('is_active', true)->count() }}</div>
        <div class="summary-item"><strong>Nonaktif:</strong> {{ $items->where('is_active', false)->count() }}</div>
        <div class="summary-item"><strong>Total Artikel:</strong> {{ $items->sum('articles_count') }}</div>
    </div>
@endsection
