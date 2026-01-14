@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA KATEGORI</h3>
        <p>Periode: @if($has_date_filter ?? false){{ $date_from ?: '-' }} s/d {{ $date_to ?: '-' }}@else Semua Data @endif</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 25%;">Nama Kategori</th>
                <th style="width: 20%;">Slug</th>
                <th style="width: 15%;">Jumlah Berita</th>
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
                            <span class="badge badge-success">AKTIF</span>
                        @else
                            <span class="badge badge-secondary">NONAKTIF</span>
                        @endif
                    </td>
                    <td class="center">{{ $category->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    </table>
@endsection
