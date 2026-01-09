@extends('reports.pdf.layout')

@section('content')
    <div class="judul">
        <h3>LAPORAN DATA PENGGUNA</h3>
        <p>Periode: {{ $date_from ?? '-' }} s/d {{ $date_to ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="number">No</th>
                <th style="width: 22%;">Nama</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 12%;">Role</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 18%;">Login Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $user)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="center">
                        @switch($user->role)
                            @case('super_admin')
                                <span class="badge badge-danger">SUPER ADMIN</span>
                                @break
                            @case('admin')
                                <span class="badge badge-warning">ADMIN</span>
                                @break
                            @case('editor')
                                <span class="badge badge-info text-white">EDITOR</span>
                                @break
                            @case('author')
                                <span class="badge badge-success">AUTHOR</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ strtoupper($user->role) }}</span>
                        @endswitch
                    </td>
                    <td class="center">
                        @if($user->isLocked())
                            <span class="badge badge-danger">TERKUNCI</span>
                        @else
                            <span class="badge badge-success">AKTIF</span>
                        @endif
                    </td>
                    <td class="center">
                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Pengguna:</strong> {{ $items->count() }}</div>
        <div class="summary-item"><strong>Super Admin:</strong> {{ $items->where('role', 'super_admin')->count() }}</div>
        <div class="summary-item"><strong>Admin:</strong> {{ $items->where('role', 'admin')->count() }}</div>
        <div class="summary-item"><strong>Editor:</strong> {{ $items->where('role', 'editor')->count() }}</div>
        <div class="summary-item"><strong>Author:</strong> {{ $items->where('role', 'author')->count() }}</div>
    </div>
@endsection
