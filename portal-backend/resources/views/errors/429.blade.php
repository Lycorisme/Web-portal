@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('accent-color', 'violet')

@section('background-blobs')
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-violet-500/10 rounded-full blur-[120px] animate-float-slow"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px] animate-float-slow" style="animation-delay: 2s"></div>
    <div class="absolute top-[40%] left-[30%] w-[40%] h-[40%] bg-fuchsia-500/10 rounded-full blur-[150px] animate-float" style="animation-delay: -3s"></div>
@endsection

@section('icon-section')
    <div class="mb-10 inline-block relative group">
        <div class="absolute inset-0 bg-violet-500/20 rounded-full blur-xl group-hover:bg-violet-500/30 transition-all duration-500"></div>
        <div class="relative w-24 h-24 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center shadow-2xl ring-4 ring-white/5 group-hover:scale-105 transition-transform duration-500">
            <i data-lucide="zap-off" class="w-10 h-10 text-violet-500 relative z-10"></i>
            
            {{-- Decorative Orbit --}}
            <div class="absolute inset-0 rounded-full border border-violet-500/30 border-dashed animate-[spin_10s_linear_infinite]"></div>
        </div>
        
        {{-- Status Pill --}}
        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 bg-slate-900 border border-violet-500/30 rounded-full flex items-center gap-2 shadow-lg">
            <span class="w-1.5 h-1.5 rounded-full bg-violet-500 animate-pulse"></span>
            <span class="text-[10px] uppercase tracking-wider font-bold text-violet-500 font-display">429</span>
        </div>
    </div>
@endsection

@section('content')
    <h1 class="font-display text-5xl md:text-6xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-white/60 pb-3">
        Terlalu Banyak Permintaan
    </h1>
    
    <p class="text-slate-400 text-lg leading-relaxed max-w-md mx-auto font-light">
        Anda telah mengirim terlalu banyak permintaan. Mohon tunggu beberapa saat sebelum mencoba lagi.
    </p>

    {{-- Countdown Timer --}}
    <div class="mt-8 inline-flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md" x-data="{ countdown: 60 }" x-init="setInterval(() => { if(countdown > 0) countdown-- }, 1000)">
        <i data-lucide="timer" class="w-5 h-5 text-violet-400"></i>
        <span class="text-slate-300 font-medium">Coba lagi dalam</span>
        <span class="font-display font-bold text-violet-400 text-xl" x-text="countdown + 's'"></span>
    </div>
@endsection

@section('actions')
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-4">
        <button onclick="location.reload()" class="w-full sm:w-auto px-8 py-3 bg-white text-slate-950 font-bold font-display rounded-xl hover:bg-violet-50 transition-colors flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(139,92,246,0.1)] group">
            <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500 text-violet-600"></i>
            <span>Coba Lagi</span>
        </button>
        
        <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-3 bg-white/5 border border-white/10 text-slate-300 font-medium font-display rounded-xl hover:bg-white/10 hover:text-white transition-colors flex items-center justify-center gap-2">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Ke Beranda</span>
        </a>
    </div>
@endsection
