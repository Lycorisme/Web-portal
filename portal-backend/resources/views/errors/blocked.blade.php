<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - IP Terblokir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900">
<div class="min-h-screen flex items-center justify-center bg-slate-900 p-4">
    <div class="max-w-md w-full text-center">
        {{-- Icon --}}
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto bg-red-500/20 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
        
        {{-- Title --}}
        <h1 class="text-3xl font-bold text-white mb-4">Akses Ditolak</h1>
        
        {{-- Message --}}
        <p class="text-slate-400 mb-6">
            IP Anda telah diblokir sementara karena aktivitas mencurigakan.
        </p>
        
        @if(isset($reason) && $reason)
        <div class="bg-slate-800/50 rounded-xl p-4 mb-6 border border-slate-700">
            <p class="text-sm text-slate-300">
                <span class="font-semibold text-amber-400">Alasan:</span> {{ $reason }}
            </p>
        </div>
        @endif
        
        @if(isset($blocked_until) && $blocked_until)
        <div class="bg-slate-800/50 rounded-xl p-4 mb-6 border border-slate-700">
            <p class="text-sm text-slate-300">
                <span class="font-semibold text-amber-400">Blokir berakhir:</span> 
                {{ $blocked_until->format('d M Y, H:i') }} WIB
            </p>
            <p class="text-xs text-slate-500 mt-2">
                ({{ $blocked_until->diffForHumans() }})
            </p>
        </div>
        @else
        <div class="bg-slate-800/50 rounded-xl p-4 mb-6 border border-slate-700">
            <p class="text-sm text-red-400">
                <span class="font-semibold">Status:</span> Blokir Permanen
            </p>
            <p class="text-xs text-slate-500 mt-2">
                Hubungi administrator untuk informasi lebih lanjut.
            </p>
        </div>
        @endif
        
        {{-- Help Text --}}
        <p class="text-xs text-slate-500">
            Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.
        </p>
        
        {{-- Back Button --}}
        <a href="{{ url('/') }}" 
           class="inline-flex items-center gap-2 mt-8 px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</div>
</body>
</html>
