{{-- Login Left Panel --}}
<div class="flex-none w-[50%] relative hidden md:flex flex-col justify-between p-12 lg:p-16 border-r border-white/5 overflow-hidden">
    {{-- Background effects --}}
    <div class="absolute inset-0 bg-gradient-to-br from-white/[0.03] to-transparent pointer-events-none"></div>
    
    {{-- Animated rings --}}
    <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] border border-white/5 rounded-full opacity-50 pointer-events-none"></div>
    <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-[350px] h-[350px] border border-white/5 rounded-full opacity-70 pointer-events-none"></div>

    {{-- Header: Logo/Brand --}}
    <div class="relative z-10">
        <div class="flex items-center gap-4">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-12 w-auto rounded-xl shadow-lg shadow-brand-500/20">
            @else
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/20">
                    <i data-lucide="layers" class="text-white w-7 h-7"></i>
                </div>
            @endif
            <span class="text-xl font-bold tracking-tight text-white">{{ $siteName }}</span>
        </div>
    </div>

    {{-- Middle: Hero Text --}}
    <div class="relative z-10 my-auto">
        <h1 class="font-display text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight mb-8">
            Digital <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Excellence.</span>
        </h1>
        <p class="text-lg text-slate-400 leading-relaxed font-light max-w-sm">
            Satu platform terintegrasi untuk mengelola seluruh ekosistem digital Anda dengan keamanan tingkat tinggi.
        </p>
    </div>

    {{-- Footer: Badges --}}
    <div class="relative z-10 mt-auto pt-8">
        <div class="flex gap-4">
            <div class="px-5 py-2.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm flex items-center gap-3 group hover:bg-white/10 transition-colors cursor-default">
                <div class="w-2 h-2 rounded-full bg-brand-400 shadow-[0_0_10px_theme(colors.brand.400)]"></div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-slate-300 group-hover:text-white transition-colors">Secure Access</span>
            </div>
            <div class="px-5 py-2.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm flex items-center gap-3 group hover:bg-white/10 transition-colors cursor-default">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-accent-400"></i>
                <span class="text-[10px] font-bold tracking-widest uppercase text-slate-300 group-hover:text-white transition-colors">Real-time Sync</span>
            </div>
        </div>
    </div>
</div>
