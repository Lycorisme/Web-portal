{{-- Security Tab Content --}}

{{-- Active Sessions --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group">
    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl group-hover:bg-cyan-500/10 transition-colors duration-700 pointer-events-none"></div>

    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500/20 to-sky-500/20 flex items-center justify-center shadow-lg shadow-cyan-500/10 border border-cyan-500/20">
            <i class="fas fa-shield-virus text-cyan-400 text-lg"></i>
        </div>
        <div>
            <h3 class="text-xl font-display font-bold text-white">Sesi Login Aktif</h3>
            <p class="text-xs text-slate-400 mt-1">Perangkat yang mengakses akun Anda</p>
        </div>
    </div>
    
    @if($activeSessions->count() > 0)
        <div class="space-y-4 relative z-10">
            @foreach($activeSessions as $session)
                <div class="flex items-center gap-5 p-4 rounded-2xl transition-all duration-300 {{ $session->is_current ? 'bg-emerald-500/10 border border-emerald-500/30 shadow-lg shadow-emerald-500/5' : 'bg-slate-950/40 border border-slate-800/80 hover:bg-slate-900/60' }}">
                    <div class="w-14 h-14 rounded-xl {{ $session->is_current ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-900 text-slate-500' }} flex items-center justify-center text-2xl border border-white/5 shadow-inner shrink-0">
                        <i class="fas {{ $session->device->icon }}"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <h4 class="font-bold text-base {{ $session->is_current ? 'text-white' : 'text-slate-300' }}">
                                {{ $session->device->browser }} di {{ $session->device->os }}
                            </h4>
                            @if($session->is_current)
                                <span class="px-2 py-0.5 bg-emerald-500 text-white text-[10px] font-bold rounded-md uppercase tracking-wide shadow-sm">
                                    Device Ini
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1.5" title="IP Address">
                                <i class="fas fa-network-wired text-slate-600"></i> {{ $session->ip_address }}
                            </span>
                            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                            <span class="flex items-center gap-1.5" title="Terakhir aktif">
                                <i class="far fa-clock text-slate-600"></i> {{ $session->last_activity->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-8 rounded-2xl bg-slate-950/30 border border-slate-800/50 border-dashed text-center">
            <p class="text-slate-500 font-medium">Informasi sesi detail tidak tersedia (Driver sesi bukan database)</p>
        </div>
    @endif
    
    <div class="mt-8 pt-8 border-t border-white/5 relative z-10">
        <form id="form-logout-all" @submit.prevent="submitForm('form-logout-all', '{{ route('public.profile.logout-all') }}')">
            <div class="bg-slate-950/50 rounded-2xl p-1.5 flex flex-col md:flex-row gap-2 border border-slate-800 shadow-inner">
                <input type="password" name="password" placeholder="Konfirmasi password Anda..." required
                       class="flex-1 bg-transparent border-none text-white px-4 py-3 focus:ring-0 placeholder-slate-600 font-medium">
                <button type="submit" :disabled="loading" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all border border-slate-700 disabled:opacity-50 whitespace-nowrap shadow-lg">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout Semua Device
                </button>
            </div>
            <p class="text-[10px] text-slate-500 mt-2 ml-2 italic">Tindakan ini akan mengeluarkan akun Anda dari semua perangkat lain yang sedang login.</p>
        </form>
    </div>
</div>

{{-- Login History --}}
<div class="rounded-3xl p-6 md:p-8 bg-slate-900/60 backdrop-blur-md border border-white/5 ring-1 ring-white/5 shadow-xl relative overflow-hidden group mt-8">
     <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-full blur-3xl group-hover:bg-violet-500/10 transition-colors duration-700 pointer-events-none"></div>
    
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500/20 to-fuchsia-500/20 flex items-center justify-center shadow-lg shadow-violet-500/10 border border-violet-500/20">
            <i class="fas fa-history text-violet-400 text-lg"></i>
        </div>
        <div>
            <h3 class="text-xl font-display font-bold text-white">Riwayat Login</h3>
            <p class="text-xs text-slate-400 mt-1">Aktivitas akses terbaru</p>
        </div>
    </div>
    
    @if($loginHistory->count() > 0)
        <div class="overflow-hidden rounded-2xl border border-slate-800/80 relative z-10">
            <table class="w-full text-left text-sm border-collapse">
                <tbody class="divide-y divide-slate-800/80 bg-slate-950/30">
                    @foreach($loginHistory as $log)
                        <tr class="group/row hover:bg-white/5 transition-colors">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                        @if($log->action === 'LOGIN') bg-emerald-500/10 text-emerald-400
                                        @elseif($log->action === 'LOGOUT') bg-slate-700/30 text-slate-400
                                        @elseif($log->action === 'LOGIN_FAILED') bg-rose-500/10 text-rose-400
                                        @else bg-amber-500/10 text-amber-400 @endif">
                                        <i class="fas text-xs
                                            @if($log->action === 'LOGIN') fa-sign-in-alt
                                            @elseif($log->action === 'LOGOUT') fa-power-off
                                            @elseif($log->action === 'LOGIN_FAILED') fa-times
                                            @else fa-key @endif"></i>
                                    </div>
                                    <span class="font-bold text-slate-300 group-hover/row:text-white transition-colors">
                                        {{ $log->action_label }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 text-slate-500 font-mono text-xs hidden sm:table-cell">
                                {{ $log->ip_address ?? 'Unknown' }}
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <div class="text-slate-400 font-medium text-xs">{{ $log->created_at->format('d M, Y') }}</div>
                                <div class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">{{ $log->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-slate-500 italic bg-slate-950/30 rounded-2xl border border-slate-800 border-dashed">
            Belum ada data riwayat login.
        </div>
    @endif
</div>

{{-- Delete Account --}}
<div class="rounded-3xl p-6 md:p-8 bg-rose-900/10 backdrop-blur-md border border-rose-500/10 ring-1 ring-rose-500/10 shadow-xl relative overflow-hidden mt-8">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 justify-between relative z-10">
        <div class="flex items-start gap-4 max-w-2xl">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center shrink-0 border border-rose-500/20">
                <i class="fas fa-exclamation-triangle text-rose-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white mb-2">Zona Berbahaya</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Menghapus akun Anda bersifat permanen. Semua data pribadi, komentar, dan riwayat aktivitas akan dihapus dan tidak dapat dipulihkan kembali.
                </p>
            </div>
        </div>
        
        <button @click="confirmDelete()" type="button" class="shrink-0 px-6 py-3 bg-red-600/10 hover:bg-red-600/20 text-red-500 font-bold text-xs uppercase tracking-wider rounded-xl border border-red-600/20 hover:border-red-600/40 transition-all hover:scale-105 active:scale-95 shadow-lg shadow-rose-900/20">
            <i class="fas fa-trash-alt mr-2"></i> Hapus Akun Saya
        </button>
    </div>
</div>
