@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN HISTORI AKTIVITAS</h3>
        <p>Periode: {{ $date_from ?? '-' }} s/d {{ $date_to ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 15%;">User</th>
                <th style="width: 12%;">Action</th>
                <th style="width: 33%;">Deskripsi</th>
                <th style="width: 15%;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $log)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td class="center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td class="center">
                        @switch($log->action)
                            @case('CREATE')
                                <span class="badge badge-success">CREATE</span>
                                @break
                            @case('UPDATE')
                                <span class="badge badge-info">UPDATE</span>
                                @break
                            @case('DELETE')
                                <span class="badge badge-danger">DELETE</span>
                                @break
                            @case('LOGIN')
                                <span class="badge badge-success">LOGIN</span>
                                @break
                            @case('LOGIN_FAILED')
                                <span class="badge badge-danger">LOGIN GAGAL</span>
                                @break
                            @case('LOGOUT')
                                <span class="badge badge-secondary">LOGOUT</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ $log->action }}</span>
                        @endswitch
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($log->description, 80) }}</td>
                    <td class="center">{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data activity log.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Log:</strong> {{ $items->count() }}</div>
        <div class="summary-item"><strong>CREATE:</strong> {{ $items->where('action', 'CREATE')->count() }}</div>
        <div class="summary-item"><strong>UPDATE:</strong> {{ $items->where('action', 'UPDATE')->count() }}</div>
        <div class="summary-item"><strong>DELETE:</strong> {{ $items->where('action', 'DELETE')->count() }}</div>
        <div class="summary-item"><strong>LOGIN:</strong> {{ $items->where('action', 'LOGIN')->count() }}</div>
    </div>
@endsection
