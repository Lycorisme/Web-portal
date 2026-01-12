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

    <form @submit.prevent="submitRegister" class="space-y-5">
        <div class="space-y-2">
            <label for="name" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Nama Lengkap</label>
            <input type="text" 
                   id="name"
                   x-model="registerName"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                   placeholder="John Doe">
        </div>

        <div class="space-y-2">
            <label for="reg_email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email</label>
            <input type="email" 
                   id="reg_email"
                   x-model="registerEmail"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                   placeholder="nama@email.com">
        </div>

        <div class="space-y-2">
            <label for="reg_password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Password</label>
            <div class="relative">
                <input :type="showRegisterPassword ? 'text' : 'password'" 
                       id="reg_password"
                       x-model="registerPassword"
                       required
                       class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest pr-12" 
                       placeholder="••••••••">
                <button type="button" @click="showRegisterPassword = !showRegisterPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                    <i :data-lucide="showRegisterPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                </button>
            </div>
            
            {{-- Strength Meter --}}
            <div class="flex gap-1 h-1 mt-2" x-show="registerPassword.length > 0">
                <div class="flex-1 rounded-full transition-colors duration-300" :class="registerPasswordStrength >= 1 ? 'bg-red-500' : 'bg-slate-700'"></div>
                <div class="flex-1 rounded-full transition-colors duration-300" :class="registerPasswordStrength >= 2 ? 'bg-orange-500' : 'bg-slate-700'"></div>
                <div class="flex-1 rounded-full transition-colors duration-300" :class="registerPasswordStrength >= 3 ? 'bg-yellow-500' : 'bg-slate-700'"></div>
                <div class="flex-1 rounded-full transition-colors duration-300" :class="registerPasswordStrength >= 4 ? 'bg-lime-500' : 'bg-slate-700'"></div>
            </div>
            <p class="text-[10px] text-slate-400 text-right mt-1" x-text="passwordStrengthText"></p>
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Konfirmasi Password</label>
            <input :type="showRegisterPassword ? 'text' : 'password'" 
                   id="password_confirmation"
                   x-model="registerPasswordConfirmation"
                   required
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest" 
                   placeholder="••••••••">
        </div>

        <div class="p-4 rounded-xl bg-white/5 border border-white/5 mt-2 transition-all" :class="{'border-brand-500/50 bg-brand-500/5': agreed}">
            <label class="flex items-start gap-3 cursor-pointer group">
                <div class="w-5 h-5 rounded border flex items-center justify-center transition-colors relative shrink-0 mt-0.5"
                     :class="agreed ? 'bg-brand-500 border-brand-500' : 'border-slate-600 bg-slate-800 group-hover:border-slate-400'">
                    <input type="checkbox" x-model="agreed" required class="absolute inset-0 opacity-0 cursor-pointer">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-white transition-opacity" :class="agreed ? 'opacity-100' : 'opacity-0'"></i>
                </div>
                <span class="text-xs leading-relaxed group-hover:text-slate-300" :class="agreed ? 'text-brand-100' : 'text-slate-400'">
                    Saya menyetujui seluruh kebijakan privasi dan aturan keamanan data yang berlaku di {{ $siteName ?? 'Portal BTIKP' }}.
                </span>
            </label>
        </div>

        <button type="submit" 
                :disabled="!agreed || isLoading"
                class="w-full py-4 rounded-xl font-bold text-sm tracking-widest shadow-lg transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                :class="agreed ? 'bg-gradient-to-r from-brand-600 to-accent-600 text-white shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98]' : 'bg-slate-800 text-slate-500 cursor-not-allowed'">
            <span x-show="!isLoading">Daftar Sekarang</span>
            <span x-show="isLoading" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </form>
</div>
