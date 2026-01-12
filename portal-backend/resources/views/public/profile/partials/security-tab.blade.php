{{-- Security Tab Content --}}

{{-- Active Sessions --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center">
            <i class="fas fa-desktop text-cyan-500"></i>
        </div>
        Sesi Aktif
    </h3>
    
    @if($activeSessions->count() > 0)
        <div class="space-y-3">
            @foreach($activeSessions as $session)
                <div class="flex items-center gap-4 p-4 rounded-xl {{ $session->is_current ? 'bg-emerald-500/10 border border-emerald-500/30' : 'bg-slate-800/30 border border-slate-700/30' }}">
                    <div class="w-12 h-12 rounded-xl {{ $session->is_current ? 'bg-emerald-500/20' : 'bg-slate-800' }} flex items-center justify-center">
                        <i class="fas {{ $session->device->icon }} text-xl {{ $session->is_current ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-bold {{ $session->is_current ? 'text-emerald-400' : 'text-white' }}">
                                {{ $session->device->browser }} di {{ $session->device->os }}
                            </span>
                            @if($session->is_current)
                                <span class="px-2 py-0.5 bg-emerald-500 text-white text-[10px] font-bold rounded-full uppercase">Saat Ini</span>
                            @endif
                        </div>
                        <div class="text-sm text-slate-500">
                            <span>{{ $session->ip_address }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $session->last_activity->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-slate-500 text-center py-6">Data sesi tidak tersedia (menggunakan file-based session)</p>
    @endif
    
    <div class="mt-6 pt-6 border-t border-slate-800">
        <form id="form-logout-all" @submit.prevent="submitForm('form-logout-all', '{{ route('public.profile.logout-all') }}')">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex-1">
                    <input type="password" name="password" placeholder="Masukkan password untuk konfirmasi" required
                           class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500/50 transition-all">
                </div>
                <button type="submit" :disabled="loading" class="px-6 py-3 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 font-bold rounded-xl border border-cyan-500/30 transition-all whitespace-nowrap disabled:opacity-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar dari Semua Perangkat
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Login History --}}
<div class="glass-card rounded-2xl p-6">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center">
            <i class="fas fa-history text-violet-500"></i>
        </div>
        Log Aktivitas Keamanan
    </h3>
    
    @if($loginHistory->count() > 0)
        <div class="space-y-2">
            @foreach($loginHistory as $log)
                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-800/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center
                        @if($log->action === 'LOGIN') bg-emerald-500/10 text-emerald-400
                        @elseif($log->action === 'LOGOUT') bg-blue-500/10 text-blue-400
                        @elseif($log->action === 'LOGIN_FAILED') bg-rose-500/10 text-rose-400
                        @else bg-amber-500/10 text-amber-400 @endif">
                        <i class="fas 
                            @if($log->action === 'LOGIN') fa-sign-in-alt
                            @elseif($log->action === 'LOGOUT') fa-sign-out-alt
                            @elseif($log->action === 'LOGIN_FAILED') fa-times-circle
                            @else fa-key @endif"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="font-semibold text-white">{{ $log->action_label }}</span>
                        <div class="text-xs text-slate-500">
                            {{ $log->ip_address ?? 'Unknown IP' }}
                        </div>
                    </div>
                    <div class="text-right text-xs text-slate-500">
                        {{ $log->created_at->format('d M Y') }}<br>
                        {{ $log->created_at->format('H:i') }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-slate-500 text-center py-6">Belum ada riwayat aktivitas</p>
    @endif
</div>

{{-- Delete Account --}}
<div class="glass-card rounded-2xl p-6 border-rose-500/20">
    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center">
            <i class="fas fa-trash-alt text-rose-500"></i>
        </div>
        Zona Berbahaya
    </h3>
    
    <div class="p-4 rounded-xl bg-rose-500/5 border border-rose-500/20">
        <h4 class="font-bold text-rose-400 mb-2">Hapus Akun</h4>
        <p class="text-slate-400 text-sm mb-4">
            Setelah akun dihapus, semua data termasuk komentar dan riwayat akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
        </p>
        <button @click="confirmDelete()" type="button" class="px-6 py-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold rounded-xl border border-rose-500/30 transition-all">
            <i class="fas fa-trash-alt mr-2"></i> Hapus Akun Saya
        </button>
    </div>
</div>
