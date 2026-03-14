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

    @php $no = 1; @endphp
    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 55%; text-align: left; padding-left: 8px;">Indikator</th>
                <th style="width: 35%;">Jumlah / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            {{-- === SECTION: Pengguna === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Pengguna
                </td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Pengguna Terdaftar</td>
                <td class="center"><strong>{{ number_format($overview['total_users']) }}</strong></td>
            </tr>
            @foreach($usersByRole as $role)
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">
                    @php
                        $roleLabel = match($role->role) {
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            'editor' => 'Editor',
                            'author' => 'Author',
                            'member' => 'Member',
                            default => ucfirst($role->role)
                        };
                    @endphp
                    — {{ $roleLabel }}
                </td>
                <td class="center">{{ number_format($role->total) }}</td>
            </tr>
            @endforeach

            {{-- === SECTION: Artikel / Berita === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Artikel / Berita
                </td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Artikel</td>
                <td class="center"><strong>{{ number_format($overview['total_articles']) }}</strong></td>
            </tr>
            @foreach($articlesByStatus as $status)
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">
                    — Status: {{ ucfirst($status->status) }}
                </td>
                <td class="center">{{ number_format($status->total) }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Kategori</td>
                <td class="center"><strong>{{ number_format($overview['total_categories']) }}</strong></td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Views Keseluruhan</td>
                <td class="center"><strong>{{ number_format($overview['total_views']) }}</strong></td>
            </tr>

            {{-- === SECTION: Media Gallery === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Media Gallery
                </td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Media</td>
                <td class="center"><strong>{{ number_format($galleryStats['total']) }}</strong></td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">— Gambar (Image)</td>
                <td class="center">{{ number_format($galleryStats['images']) }}</td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">— Video</td>
                <td class="center">{{ number_format($galleryStats['videos']) }}</td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">— Sudah Dipublikasikan</td>
                <td class="center">{{ number_format($galleryStats['published']) }}</td>
            </tr>

            {{-- === SECTION: Interaksi Publik === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Interaksi Publik
                </td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Komentar</td>
                <td class="center"><strong>{{ number_format($interactionStats['total_comments']) }}</strong></td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">— Komentar Terlihat (Visible)</td>
                <td class="center">{{ number_format($interactionStats['visible_comments']) }}</td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 20px;">— Komentar Spam Terdeteksi</td>
                <td class="center">{{ number_format($interactionStats['spam_comments']) }}</td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Likes</td>
                <td class="center"><strong>{{ number_format($interactionStats['total_likes']) }}</strong></td>
            </tr>

            {{-- === SECTION: Keamanan === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Keamanan
                </td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">IP Terblokir Aktif</td>
                <td class="center"><strong>{{ number_format($securityStats['active_blocks']) }}</strong></td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total IP Pernah Terblokir</td>
                <td class="center">{{ number_format($securityStats['total_blocked_ever']) }}</td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Login Gagal (7 Hari Terakhir)</td>
                <td class="center">{{ number_format($securityStats['failed_logins_7d']) }}</td>
            </tr>

            {{-- === SECTION: Activity Log === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Activity Log
                </td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Aktivitas (7 Hari Terakhir)</td>
                <td class="center"><strong>{{ number_format($activityStats['total_7d']) }}</strong></td>
            </tr>
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">Total Aktivitas (Keseluruhan)</td>
                <td class="center">{{ number_format($activityStats['total_all']) }}</td>
            </tr>

            {{-- === SECTION: Top 5 Artikel Terpopuler === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Top 5 Artikel Terpopuler
                </td>
            </tr>
            @forelse($topArticles as $article)
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">{{ \Illuminate\Support\Str::limit($article->title, 55) }}</td>
                <td class="center">{{ number_format($article->views ?? 0) }} views</td>
            </tr>
            @empty
            <tr>
                <td class="number">-</td>
                <td style="text-align: left; padding-left: 8px; font-style: italic;">Belum ada data artikel</td>
                <td class="center">-</td>
            </tr>
            @endforelse

            {{-- === SECTION: Top 5 Kategori Paling Aktif === --}}
            <tr>
                <td colspan="3" style="background-color: #E5E7EB; font-weight: bold; text-align: left; padding-left: 8px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px;">
                    Top 5 Kategori Paling Aktif
                </td>
            </tr>
            @forelse($topCategories as $category)
            <tr>
                <td class="number">{{ $no++ }}</td>
                <td style="text-align: left; padding-left: 8px;">{{ $category->name }}</td>
                <td class="center">{{ number_format($category->articles_count) }} artikel</td>
            </tr>
            @empty
            <tr>
                <td class="number">-</td>
                <td style="text-align: left; padding-left: 8px; font-style: italic;">Belum ada data kategori</td>
                <td class="center">-</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
