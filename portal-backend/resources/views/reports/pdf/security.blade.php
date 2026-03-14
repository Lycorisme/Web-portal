@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN KEAMANAN & IP TERBLOKIR</h3>
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

    {{-- Security Summary --}}
    <div style="margin-bottom: 20px;">
        <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">A. Ringkasan Keamanan</h4>
        <table style="width: 50%; border: none; font-size: 10pt;">
            <tr>
                <td style="border: none; padding: 2px 0; width: 55%;"><strong>Total IP Terblokir (Aktif)</strong></td>
                <td style="border: none; padding: 2px 0; width: 5%;">:</td>
                <td style="border: none; padding: 2px 0;">{{ $security_summary['active_blocks'] }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total IP Pernah Terblokir</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ $security_summary['total_blocked'] }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Total Login Gagal (All Time)</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ $security_summary['total_failed_logins'] }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 0;"><strong>Login Gagal (7 Hari Terakhir)</strong></td>
                <td style="border: none; padding: 2px 0;">:</td>
                <td style="border: none; padding: 2px 0;">{{ $security_summary['recent_failed_logins'] }}</td>
            </tr>
        </table>
    </div>

    {{-- Blocked Clients Table --}}
    <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">B. Daftar IP Tercatat</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 14%;">IP Address</th>
                <th style="width: 14%; text-align: left; padding-left: 8px;">User Terkait</th>
                <th style="width: 18%; text-align: left; padding-left: 8px;">Alasan</th>
                <th style="width: 8%;">Percobaan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 14%;">Diblokir Sampai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $client)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td class="center" style="font-size: 8pt;">{{ $client->ip_address }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $client->user_name ?? $client->user?->name ?? '-' }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ \Illuminate\Support\Str::limit($client->reason ?? '-', 40) }}</td>
                    <td class="center">{{ $client->attempt_count }}</td>
                    <td class="center">
                        @if($client->is_blocked)
                            <span class="badge badge-danger">TERBLOKIR</span>
                        @else
                            <span class="badge badge-success">AMAN</span>
                        @endif
                    </td>
                    <td class="center">
                        @if($client->blocked_until)
                            {{ $client->blocked_until->format('d/m/Y H:i') }}
                        @else
                            {{ $client->is_blocked ? 'Permanen' : '-' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data IP tercatat pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Security Logs --}}
    @if($security_logs->count() > 0)
        <div style="page-break-before: auto; margin-top: 30px;">
            <h4 style="font-size: 11pt; margin-bottom: 8px; text-decoration: underline;">C. Log Keamanan Terkini</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="number">No</th>
                        <th style="width: 16%;">Tanggal</th>
                        <th style="width: 16%; text-align: left; padding-left: 8px;">User</th>
                        <th style="width: 14%;">Aksi</th>
                        <th style="width: 30%; text-align: left; padding-left: 8px;">Deskripsi</th>
                        <th style="width: 14%;">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($security_logs as $index => $log)
                        <tr>
                            <td class="number">{{ $index + 1 }}</td>
                            <td class="center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td style="text-align: left; padding-left: 8px;">{{ $log->user->name ?? 'Guest' }}</td>
                            <td class="center">
                                @php
                                    $actionClass = match($log->action) {
                                        'LOGIN_FAILED' => 'badge-danger',
                                        'LOGIN' => 'badge-success',
                                        'LOGOUT' => 'badge-info',
                                        'PASSWORD_CHANGE' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $actionClass }}">{{ $log->action }}</span>
                            </td>
                            <td style="text-align: left; padding-left: 8px;">{{ \Illuminate\Support\Str::limit($log->description, 50) }}</td>
                            <td class="center" style="font-size: 8pt;">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
