{{-- Verify Email Partial --}}
<div x-show="mode === 'verify'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-cloak
     class="flex flex-col h-full justify-center">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500/20 to-accent-500/20 text-brand-400 mb-6 border border-white/5 shadow-inner animate-float">
            <i data-lucide="mail-check" class="w-8 h-8"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Verifikasi Email</h2>
        <p class="text-slate-400 text-sm">Kode OTP telah dikirim ke <span class="text-white font-medium" x-text="verifyEmail"></span></p>
    </div>

    {{-- OTP Input --}}
    <form @submit.prevent="submitVerify" class="space-y-6">
        <div>
            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2 block text-center">Kode Verifikasi (OTP)</label>
            <div class="flex justify-center gap-2 sm:gap-3">
                <template x-for="(digit, index) in verifyOtpDigits" :key="index">
                    <input type="text" 
                           :id="'verify-otp-' + index"
                           x-model="verifyOtpDigits[index]"
                           @input="handleVerifyOtpInput($event, index)"
                           @keydown.backspace="handleVerifyOtpBackspace($event, index)"
                           @paste="handleVerifyOtpPaste($event)"
                           maxlength="1"
                           class="w-10 h-12 sm:w-12 sm:h-14 rounded-lg bg-slate-800/50 border border-slate-700 text-center text-xl font-bold text-white focus:bg-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition-all"
                           :class="{'border-red-500': errorMessage}">
                </template>
            </div>
        </div>

        <button type="submit" 
                :disabled="isLoading"
                class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 uppercase">
            <span x-show="!isLoading">Verifikasi Akun</span>
            <span x-show="isLoading" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-slate-500 mb-2" x-show="verifyCountdown > 0">
            Kirim ulang dalam <span class="font-mono text-brand-400 font-bold" x-text="formatVerifyCountdown"></span>
        </p>
        <button type="button" 
                @click="resendVerifyOtp" 
                x-show="verifyCountdown === 0"
                class="text-sm text-brand-400 font-bold hover:text-brand-300 hover:underline transition-all">
            Kirim Ulang Kode
        </button>
        
        <div class="mt-8 pt-6 border-t border-white/5">
            <button @click="switchMode('login')" class="text-xs font-medium text-slate-500 hover:text-white transition-colors flex items-center justify-center gap-2 group w-full">
                <i data-lucide="arrow-left" class="w-3 h-3 group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Login
            </button>
        </div>
    </div>
</div>
