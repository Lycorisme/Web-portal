{{-- Register Form Partial --}}
<div x-show="mode === 'register'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-cloak>
    
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Akun Baru</h2>
        <p class="text-slate-400">Daftarkan diri Anda ke dalam sistem.</p>
    </div>

    <form action="#" class="space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Nama Depan</label>
                <input type="text" class="w-full px-4 py-3.5 rounded-xl modern-input text-white" placeholder="John">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Nama Belakang</label>
                <input type="text" class="w-full px-4 py-3.5 rounded-xl modern-input text-white" placeholder="Doe">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email Dinas</label>
            <input type="email" class="w-full px-5 py-4 rounded-xl modern-input text-white" placeholder="nama@instansi.go.id">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">NIP / ID Pegawai</label>
            <input type="text" class="w-full px-5 py-4 rounded-xl modern-input text-white" placeholder="199XXXXXXXX">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Password</label>
            <input type="password" class="w-full px-5 py-4 rounded-xl modern-input text-white" placeholder="••••••••">
        </div>

        <div class="p-4 rounded-xl bg-white/5 border border-white/5 mt-2">
            <label class="flex items-start gap-3 cursor-pointer group">
                <div class="w-5 h-5 rounded border border-slate-600 bg-slate-800 flex items-center justify-center transition-colors group-hover:border-brand-500 relative shrink-0 mt-0.5">
                    <input type="checkbox" class="absolute inset-0 opacity-0 cursor-pointer peer">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                </div>
                <span class="text-xs text-slate-400 leading-relaxed group-hover:text-slate-300">
                    Saya menyetujui seluruh kebijakan privasi dan aturan keamanan data yang berlaku di {{ $siteName }}.
                </span>
            </label>
        </div>

        <button class="w-full py-4 rounded-xl bg-slate-800 border border-white/10 hover:bg-white/5 text-white font-bold text-sm tracking-widest transition-all duration-300 uppercase mt-4">
            Ajukan Pendaftaran
        </button>
    </form>
</div>
