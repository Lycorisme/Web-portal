{{-- Profile Header Card --}}
<div class="rounded-[2.5rem] p-8 mb-10 relative overflow-hidden bg-slate-900/40 backdrop-blur-xl border border-white/5 shadow-2xl ring-1 ring-white/5 group">
    
    {{-- Decorative Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-transparent to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
    
    <div class="relative flex flex-col md:flex-row items-center gap-8 md:gap-12 z-10">
        {{-- Avatar Section --}}
        <div class="relative shrink-0">
            <div class="w-32 h-32 md:w-36 md:h-36 rounded-full overflow-hidden ring-4 ring-emerald-500/20 shadow-2xl relative z-10 group-hover:ring-emerald-500/40 transition-all duration-500 transform group-hover:scale-105 bg-slate-800">
                @if($user->profile_photo)
                    <img src="{{ $user->avatar }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" id="header-avatar">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white text-5xl font-bold font-display" id="header-avatar-placeholder">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            {{-- Status Badge (Absolute) --}}
            <div class="absolute bottom-1 right-1 md:bottom-2 md:right-2 z-20">
                 @if($user->email_verified_at)
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500 rounded-full border-[3px] border-slate-900 flex items-center justify-center shadow-lg transform translate-y-1 translate-x-1" title="Terverifikasi">
                        <i class="fas fa-check text-white text-xs md:text-sm font-bold"></i>
                    </div>
                @else
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-amber-500 rounded-full border-[3px] border-slate-900 flex items-center justify-center shadow-lg transform translate-y-1 translate-x-1 animate-pulse" title="Belum Verifikasi">
                        <i class="fas fa-exclamation text-white text-xs md:text-sm font-bold"></i>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- User Info Section --}}
        <div class="text-center md:text-left flex-1 min-w-0">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-2 tracking-tight drop-shadow-sm" id="header-name">{{ $user->name }}</h1>
            <p class="text-slate-400 text-lg mb-6 font-medium tracking-wide" id="header-email">{{ $user->email }}</p>
            
            <div class="flex flex-wrap justify-center md:justify-start gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-emerald-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-emerald-500/5 backdrop-blur-sm">
                    <i class="fas fa-user-circle"></i> Member Area
                </span>
                
                @if($user->email_verified_at)
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-500/5 backdrop-blur-sm">
                        <i class="fas fa-shield-alt"></i> Akun Terverifikasi
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 text-xs font-bold uppercase tracking-widest shadow-lg shadow-amber-500/5 backdrop-blur-sm animate-pulse">
                        <i class="fas fa-lock"></i> Perlu Verifikasi
                    </span>
                @endif
            </div>
        </div>
        
        {{-- Quick Stats Section --}}
        <div class="flex gap-8 md:gap-12 md:pr-8 py-4 md:py-0 border-t md:border-t-0 md:border-l border-white/5 w-full md:w-auto justify-center md:justify-end">
            <div class="text-center group/stat cursor-default">
                <div class="text-3xl font-display font-bold text-white mb-1 group-hover/stat:text-emerald-400 transition-colors">{{ $stats['likes_count'] }}</div>
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover/stat:text-slate-300 transition-colors">Disukai</div>
            </div>
            <div class="text-center group/stat cursor-default">
                <div class="text-3xl font-display font-bold text-white mb-1 group-hover/stat:text-blue-400 transition-colors">{{ $stats['comments_count'] }}</div>
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover/stat:text-slate-300 transition-colors">Komentar</div>
            </div>
        </div>
    </div>
</div>
