{{-- Register Form Partial --}}
<div x-show="mode === 'register'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-cloak>
    
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Akun Baru</h2>
        <p class="text-slate-400">Daftarkan diri Anda untuk berinteraksi di portal.</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf
        <div class="space-y-2">
            <label for="name" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Nama Lengkap</label>
            <input type="text" 
                   id="name"
                   name="name" 
                   value="{{ old('name') }}"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium @error('name') border-red-500 @enderror" 
                   placeholder="John Doe">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="reg_email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email</label>
            <input type="email" 
                   id="reg_email"
                   name="email" 
                   value="{{ old('email') }}"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium @error('email') border-red-500 @enderror" 
                   placeholder="nama@email.com">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="reg_password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Password</label>
            <input type="password" 
                   id="reg_password"
                   name="password"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest @error('password') border-red-500 @enderror" 
                   placeholder="••••••••">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Konfirmasi Password</label>
            <input type="password" 
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest" 
                   placeholder="••••••••">
        </div>

        <div class="p-4 rounded-xl bg-white/5 border border-white/5 mt-2">
            <label class="flex items-start gap-3 cursor-pointer group">
                <div class="w-5 h-5 rounded border border-slate-600 bg-slate-800 flex items-center justify-center transition-colors group-hover:border-brand-500 relative shrink-0 mt-0.5">
                    <input type="checkbox" required class="absolute inset-0 opacity-0 cursor-pointer peer">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                </div>
                <span class="text-xs text-slate-400 leading-relaxed group-hover:text-slate-300">
                    Saya menyetujui seluruh kebijakan privasi dan aturan keamanan data yang berlaku di {{ $siteName ?? 'Portal BTIKP' }}.
                </span>
            </label>
        </div>

        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4">
            Daftar Sekarang
        </button>
    </form>
</div>
