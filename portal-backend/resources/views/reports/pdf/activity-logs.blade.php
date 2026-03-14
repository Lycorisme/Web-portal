@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN ACTIVITY LOG</h3>
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
                <td style="border: none; padding: 1px;">{{ $items->count() }} aktivitas</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 14%; text-align: left; padding-left: 8px;">User</th>
                <th style="width: 12%;">Aksi</th>
                <th style="width: 28%; text-align: left; padding-left: 8px;">Deskripsi</th>
                <th style="width: 8%;">Level</th>
                <th style="width: 12%;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $log)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td class="center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $log->user->name ?? 'System' }}</td>
                    <td class="center">
                        <span class="badge badge-info">{{ $log->action }}</span>
                    </td>
                    <td style="text-align: left; padding-left: 8px;">{{ \Illuminate\Support\Str::limit($log->description, 60) }}</td>
                    <td class="center">
                        @php
                            $levelClass = match($log->level) {
                                'info' => 'badge-info',
                                'warning' => 'badge-warning',
                                'danger' => 'badge-danger',
                                'critical' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $levelClass }}">{{ strtoupper($log->level) }}</span>
                    </td>
                    <td class="center" style="font-size: 8pt;">{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data aktivitas pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
