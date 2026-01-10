<div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden group">
     <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
        <i data-lucide="shield" class="w-24 h-24 transform translate-x-8 -translate-y-8"></i>
    </div>
    
    @php $score = $securityScore ?? 100; @endphp
    
    <div class="flex items-center gap-3 mb-4">
         <div class="w-10 h-10 rounded-lg {{ $score >= 80 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }} flex items-center justify-center backdrop-blur-sm">
             <i data-lucide="{{ $score >= 80 ? 'lock' : 'alert-triangle' }}" class="w-5 h-5"></i>
         </div>
         <div>
             <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Keamanan</p>
             <h4 class="font-bold text-lg">{{ $score >= 80 ? 'Sistem Aman' : 'Perlu Tindakan' }}</h4>
         </div>
    </div>

    <div class="space-y-3 relative z-10">
        <div class="flex items-center justify-between text-sm border-b border-white/10 pb-2">
             <span class="text-slate-300">Blocked IP</span>
             <span class="font-mono {{ ($stats['blocked_ips'] ?? 0) > 0 ? 'text-amber-400' : 'text-emerald-400' }}">{{ $stats['blocked_ips'] ?? 0 }}</span>
        </div>
        <div class="flex items-center justify-between text-sm border-b border-white/10 pb-2">
             <span class="text-slate-300">Failed Logins</span>
             <span class="font-mono {{ ($stats['failed_logins'] ?? 0) > 0 ? 'text-amber-400' : 'text-emerald-400' }}">{{ $stats['failed_logins'] ?? 0 }}</span>
        </div>
         <div class="flex items-center justify-between text-sm">
             <span class="text-slate-300">Firewall</span>
             <span class="text-emerald-400 font-bold text-xs uppercase">Active</span>
        </div>
    </div>
</div>
