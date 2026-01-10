@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA IP TERBLOKIR</h3>
        <p>Periode: {{ $date_from ?? '-' }} s/d {{ $date_to ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 15%;">IP Address</th>
                <th style="width: 23%;">Alasan</th>
                <th style="width: 17%;">Diblokir Sampai</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 25%;">User Agent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $client)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $client->ip_address }}</td>
                    <td>{{ $client->reason ?? '-' }}</td>
                    <td class="center">
                        @if($client->blocked_until)
                            {{ $client->blocked_until->format('d/m/Y H:i') }}
                        @else
                            <span style="color: #dc3545;">Permanen</span>
                        @endif
                    </td>
                    <td class="center">
                        @if($client->is_blocked)
                            @if($client->isExpired())
                                <span class="badge badge-secondary">EXPIRED</span>
                            @else
                                <span class="badge badge-danger">TERBLOKIR</span>
                            @endif
                        @else
                            <span class="badge badge-success">CLEAN</span>
                        @endif
                    </td>
                    <td style="font-size: 8px; word-break: break-all;">
                        {{ \Illuminate\Support\Str::limit($client->user_agent, 60) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data IP terblokir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    </table>
@endsection
