@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN INTERAKSI PUBLIK</h3>
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
        </table>
    </div>

    {{-- Summary Box --}}
    <div style="margin-bottom: 20px;">
        <table style="width: 50%; border: none; font-size: 10pt;">
            <tr>
                <td style="border: none; padding: 2px 0; width: 50%;"><strong>Total Artikel Terpublikasi</strong></td>
                <td style="border: none; padding: 2px 0; width: 5%;">:</td>
                <td style="border: none; padding: 2px 0;">{{ number_format($summary['total_articles']) }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Views Keseluruhan</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ number_format($summary['total_views']) }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Komentar</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ number_format($summary['total_comments']) }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Komentar Spam</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ number_format($summary['total_spam']) }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Likes</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ number_format($summary['total_likes']) }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 28%; text-align: left; padding-left: 8px;">Judul Artikel</th>
                <th style="width: 8%;">Views</th>
                <th style="width: 10%;">Total Komentar</th>
                <th style="width: 10%;">Komentar Spam</th>
                <th style="width: 10%;">Total Like</th>
                <th style="width: 18%; text-align: left; padding-left: 8px;">Komentator Terbanyak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $article)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ \Illuminate\Support\Str::limit($article->title, 50) }}</td>
                    <td class="center">{{ number_format($article->views ?? 0) }}</td>
                    <td class="center">{{ $article->comments_count }}</td>
                    <td class="center">
                        @if($article->spam_comments_count > 0)
                            <span class="badge badge-danger">{{ $article->spam_comments_count }}</span>
                        @else
                            0
                        @endif
                    </td>
                    <td class="center">{{ $article->likes_count }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $article->top_commenter_name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data interaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
