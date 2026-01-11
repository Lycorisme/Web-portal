{{-- Login Form Partial --}}
<div x-show="mode === 'login'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-400">Masuk untuk mengakses dashboard Anda.</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        <div class="space-y-2">
            <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Identity</label>
            <input type="email" 
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium @error('email') border-red-500 @enderror" 
                   placeholder="username@btikp.id">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Security Code</label>
            <input type="password" 
                   id="password"
                   name="password"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest @error('password') border-red-500 @enderror" 
                   placeholder="••••••••">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-2">
            <label class="flex items-center gap-3 cursor-pointer group" for="remember" x-data="{ checked: {{ old('remember') ? 'true' : 'false' }} }">
                <div class="w-5 h-5 rounded border flex items-center justify-center transition-all duration-200 relative"
                     :class="checked ? 'border-brand-500 bg-brand-500' : 'border-slate-600 bg-slate-800 group-hover:border-brand-500'">
                    <input type="checkbox" 
                           name="remember" 
                           id="remember" 
                           value="1"
                           class="absolute inset-0 opacity-0 cursor-pointer z-10" 
                           x-model="checked">
                    <svg x-show="checked" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-sm text-slate-400 group-hover:text-slate-300">Ingat Saya</span>
            </label>
            <button type="button" @click="switchMode('reset')" class="text-sm font-medium text-brand-400 hover:text-brand-300 transition-colors">Lupa Password?</button>
        </div>

        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4">
            Masuk Portal
        </button>
    </form>
</div>
