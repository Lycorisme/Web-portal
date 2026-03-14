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

    {{-- Section A: Ringkasan Umum --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">A. Ringkasan Umum Sistem</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 8px; width: 60%;">Indikator</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Pengguna Terdaftar</td>
                <td class="center"><strong>{{ number_format($overview['total_users']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Artikel/Berita</td>
                <td class="center"><strong>{{ number_format($overview['total_articles']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Kategori</td>
                <td class="center"><strong>{{ number_format($overview['total_categories']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Media Gallery</td>
                <td class="center"><strong>{{ number_format($overview['total_galleries']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Views Keseluruhan</td>
                <td class="center"><strong>{{ number_format($overview['total_views']) }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Section B: Pengguna per Role --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">B. Distribusi Pengguna per Role</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="text-align: left; padding-left: 8px; width: 50%;">Role</th>
                <th style="width: 30%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usersByRole as $index => $role)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">
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
                        {{ $roleLabel }}
                    </td>
                    <td class="center"><strong>{{ $role->total }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Section C: Artikel per Status --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">C. Distribusi Artikel per Status</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="text-align: left; padding-left: 8px; width: 50%;">Status</th>
                <th style="width: 30%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articlesByStatus as $index => $status)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">
                        @php
                            $statusClass = match($status->status) {
                                'published' => 'badge-success',
                                'draft' => 'badge-secondary',
                                'pending' => 'badge-warning',
                                'rejected' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ strtoupper($status->status) }}</span>
                    </td>
                    <td class="center"><strong>{{ $status->total }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Section D: Gallery --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">D. Statistik Media Gallery</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 8px; width: 60%;">Indikator</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Media</td>
                <td class="center"><strong>{{ number_format($galleryStats['total']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Gambar (Image)</td>
                <td class="center">{{ number_format($galleryStats['images']) }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Video</td>
                <td class="center">{{ number_format($galleryStats['videos']) }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Sudah Dipublikasikan</td>
                <td class="center">{{ number_format($galleryStats['published']) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Section E: Interaksi --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">E. Statistik Interaksi Publik</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 8px; width: 60%;">Indikator</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Komentar</td>
                <td class="center"><strong>{{ number_format($interactionStats['total_comments']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Komentar Terlihat (Visible)</td>
                <td class="center">{{ number_format($interactionStats['visible_comments']) }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Komentar Spam Terdeteksi</td>
                <td class="center">
                    @if($interactionStats['spam_comments'] > 0)
                        <span class="badge badge-danger">{{ number_format($interactionStats['spam_comments']) }}</span>
                    @else
                        0
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Likes</td>
                <td class="center"><strong>{{ number_format($interactionStats['total_likes']) }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Section F: Keamanan --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">F. Statistik Keamanan</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 8px; width: 60%;">Indikator</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; padding-left: 8px;">IP Terblokir Aktif</td>
                <td class="center">
                    @if($securityStats['active_blocks'] > 0)
                        <span class="badge badge-danger">{{ $securityStats['active_blocks'] }}</span>
                    @else
                        <span class="badge badge-success">0</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total IP Pernah Terblokir</td>
                <td class="center">{{ $securityStats['total_blocked_ever'] }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Login Gagal (7 Hari Terakhir)</td>
                <td class="center">{{ $securityStats['failed_logins_7d'] }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Section G: Activity Log --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">G. Statistik Activity Log</h4>
    <table class="data-table" style="width: 60%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 8px; width: 60%;">Indikator</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Aktivitas (7 Hari Terakhir)</td>
                <td class="center"><strong>{{ number_format($activityStats['total_7d']) }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 8px;">Total Aktivitas (All Time)</td>
                <td class="center">{{ number_format($activityStats['total_all']) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Section H: Top 5 Artikel Terpopuler --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">H. Top 5 Artikel Terpopuler (Views)</h4>
    <table class="data-table" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 40%; text-align: left; padding-left: 8px;">Judul Artikel</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%; text-align: left; padding-left: 8px;">Penulis</th>
                <th style="width: 12%;">Views</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topArticles as $index => $article)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ \Illuminate\Support\Str::limit($article->title, 50) }}</td>
                    <td class="center">{{ $article->categoryRelation->name ?? '-' }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $article->author->name ?? '-' }}</td>
                    <td class="center"><strong>{{ number_format($article->views ?? 0) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Belum ada data artikel.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Section I: Top 5 Kategori --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">I. Top 5 Kategori Paling Aktif</h4>
    <table class="data-table" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 50%; text-align: left; padding-left: 8px;">Nama Kategori</th>
                <th style="width: 30%;">Jumlah Artikel</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCategories as $index => $category)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $category->name }}</td>
                    <td class="center"><strong>{{ $category->articles_count }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="center">Belum ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
