@extends('errors.layout')

@section('title', 'Akses Tidak Sah')
@section('accent-color', 'rose')

@section('background-blobs')
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-rose-500/10 rounded-full blur-[120px] animate-float-slow"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-red-500/10 rounded-full blur-[120px] animate-float-slow" style="animation-delay: 2s"></div>
    <div class="absolute top-[40%] left-[30%] w-[40%] h-[40%] bg-pink-500/10 rounded-full blur-[150px] animate-float" style="animation-delay: -3s"></div>
@endsection

@section('icon-section')
    <div class="mb-10 inline-block relative group">
        <div class="absolute inset-0 bg-rose-500/20 rounded-full blur-xl group-hover:bg-rose-500/30 transition-all duration-500"></div>
        <div class="relative w-24 h-24 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center shadow-2xl ring-4 ring-white/5 group-hover:scale-105 transition-transform duration-500">
            <i data-lucide="user-x" class="w-10 h-10 text-rose-500 relative z-10"></i>
            
            {{-- Decorative Orbit --}}
            <div class="absolute inset-0 rounded-full border border-rose-500/30 border-dashed animate-[spin_10s_linear_infinite]"></div>
        </div>
        
        {{-- Status Pill --}}
        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 bg-slate-900 border border-rose-500/30 rounded-full flex items-center gap-2 shadow-lg">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
            <span class="text-[10px] uppercase tracking-wider font-bold text-rose-500 font-display">401</span>
        </div>
    </div>
@endsection

@section('content')
    <h1 class="font-display text-5xl md:text-6xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-white/60 pb-3">
        Akses Tidak Sah
    </h1>
    
    <p class="text-slate-400 text-lg leading-relaxed max-w-md mx-auto font-light">
        Anda perlu masuk ke akun Anda terlebih dahulu untuk mengakses halaman ini.
    </p>
@endsection

@section('actions')
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3 bg-white text-slate-950 font-bold font-display rounded-xl hover:bg-rose-50 transition-colors flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(244,63,94,0.1)] group">
            <i data-lucide="log-in" class="w-4 h-4 text-rose-600"></i>
            <span>Masuk ke Akun</span>
        </a>
        
        <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-3 bg-white/5 border border-white/10 text-slate-300 font-medium font-display rounded-xl hover:bg-white/10 hover:text-white transition-colors flex items-center justify-center gap-2">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Ke Beranda</span>
        </a>
    </div>
@endsection
