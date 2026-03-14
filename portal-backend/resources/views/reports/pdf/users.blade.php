@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA PENGGUNA</h3>
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
                <td style="border: none; padding: 1px;">{{ $items->count() }} pengguna</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 22%; text-align: left; padding-left: 8px;">Nama</th>
                <th style="width: 22%; text-align: left; padding-left: 8px;">Email</th>
                <th style="width: 12%;">Role</th>
                <th style="width: 12%;">Status Akun</th>
                <th style="width: 14%;">Login Terakhir</th>
                <th style="width: 14%;">Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $user)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $user->name }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $user->email }}</td>
                    <td class="center">
                        @php
                            $roleClass = match($user->role) {
                                'super_admin' => 'badge-danger',
                                'admin' => 'badge-warning',
                                'editor' => 'badge-info',
                                'author' => 'badge-success',
                                'member' => 'badge-secondary',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $roleClass }}">{{ strtoupper($user->role) }}</span>
                    </td>
                    <td class="center">
                        @if($user->isLocked())
                            <span class="badge badge-danger">TERKUNCI</span>
                        @elseif($user->trashed())
                            <span class="badge badge-secondary">NONAKTIF</span>
                        @else
                            <span class="badge badge-success">AKTIF</span>
                        @endif
                    </td>
                    <td class="center">
                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="center">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data pengguna pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
