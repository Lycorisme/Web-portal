{{-- Reset Password Form Partial --}}
<div x-show="mode === 'reset'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-cloak>

    {{-- Step 1: Email Verification --}}
    <div x-show="resetStep === 1">
        <div class="mb-8">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-6">
                <i data-lucide="mail" class="w-7 h-7 text-amber-400"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Lupa Password?</h2>
            <p class="text-slate-400">Masukkan email terdaftar Anda. Kami akan mengirimkan kode OTP untuk verifikasi.</p>
        </div>

        <form @submit.prevent="sendOtp()" class="space-y-6">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email Terdaftar</label>
                <input type="email" 
                       x-model="resetEmail"
                       required
                       class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                       placeholder="masukkan email anda">
            </div>

            <button type="submit" 
                    :disabled="isLoading"
                    class="w-full py-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-sm tracking-widest shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                <template x-if="isLoading">
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="isLoading ? 'Mengirim...' : 'Kirim Kode OTP'"></span>
            </button>

            <p class="text-center text-sm text-slate-500 mt-4">
                Ingat password? 
                <button type="button" @click="switchMode('login')" class="text-brand-400 hover:text-brand-300 font-medium">Kembali ke Login</button>
            </p>
        </form>
    </div>

    {{-- Step 2: OTP Verification --}}
    <div x-show="resetStep === 2">
        <div class="mb-8">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-6">
                <i data-lucide="smartphone" class="w-7 h-7 text-emerald-400"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Verifikasi OTP</h2>
            <p class="text-slate-400">
                Masukkan kode 6 digit yang telah dikirim ke 
                <span class="text-brand-400 font-medium" x-text="maskedEmail"></span>
            </p>
        </div>

        <form @submit.prevent="verifyOtp()" class="space-y-6">
            <div class="flex justify-center gap-3">
                <template x-for="(digit, index) in otpDigits" :key="index">
                    <input type="text" 
                           maxlength="1"
                           x-model="otpDigits[index]"
                           @input="handleOtpInput($event, index)"
                           @keydown.backspace="handleOtpBackspace($event, index)"
                           @paste="handleOtpPaste($event)"
                           :id="'otp-' + index"
                           class="w-12 h-14 text-center text-2xl font-bold rounded-xl modern-input text-white focus:border-emerald-500 focus:ring-emerald-500/20"
                           pattern="[0-9]"
                           inputmode="numeric">
                </template>
            </div>

            <div class="text-center">
                <p class="text-sm text-slate-500" x-show="countdown > 0">
                    Kode berlaku dalam <span class="text-amber-400 font-mono font-bold" x-text="formatCountdown()"></span>
                </p>
                <p class="text-sm text-red-400" x-show="countdown <= 0">
                    Kode OTP sudah kadaluarsa
                </p>
            </div>

            <button type="submit" 
                    :disabled="isLoading || otpDigits.join('').length !== 6"
                    class="w-full py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold text-sm tracking-widest shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                <template x-if="isLoading">
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="isLoading ? 'Memverifikasi...' : 'Verifikasi Kode'"></span>
            </button>

            <div class="text-center space-y-2">
                <p class="text-sm text-slate-500">
                    Tidak menerima kode? 
                    <button type="button" 
                            @click="resendOtp()"
                            :disabled="resendCooldown > 0 || isLoading"
                            class="text-brand-400 hover:text-brand-300 font-medium disabled:text-slate-600 disabled:cursor-not-allowed">
                        <span x-show="resendCooldown <= 0">Kirim Ulang</span>
                        <span x-show="resendCooldown > 0" x-text="'Tunggu ' + resendCooldown + 's'"></span>
                    </button>
                </p>
                <button type="button" @click="resetStep = 1" class="text-sm text-slate-500 hover:text-slate-300">
                    ← Ubah email
                </button>
            </div>
        </form>
    </div>

    {{-- Step 3: New Password --}}
    <div x-show="resetStep === 3">
        <div class="mb-8">
            <div class="w-14 h-14 rounded-2xl bg-brand-500/10 flex items-center justify-center mb-6">
                <i data-lucide="lock" class="w-7 h-7 text-brand-400"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Password Baru</h2>
            <p class="text-slate-400">Buat password baru yang kuat untuk akun Anda.</p>
        </div>

        <form @submit.prevent="resetPassword()" class="space-y-5">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Password Baru</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" 
                           x-model="newPassword"
                           required
                           minlength="8"
                           class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium pr-12" 
                           placeholder="Minimal 8 karakter">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                        <i data-lucide="eye" class="w-5 h-5" x-show="!showPassword"></i>
                        <i data-lucide="eye-off" class="w-5 h-5" x-show="showPassword" x-cloak></i>
                    </button>
                </div>
                <div class="flex gap-1 mt-2">
                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-slate-700'"></div>
                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 2 ? 'bg-amber-500' : 'bg-slate-700'"></div>
                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 3 ? 'bg-emerald-500' : 'bg-slate-700'"></div>
                    <div class="h-1 flex-1 rounded-full transition-colors" :class="passwordStrength >= 4 ? 'bg-brand-500' : 'bg-slate-700'"></div>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    <span x-show="passwordStrength === 0">Masukkan password</span>
                    <span x-show="passwordStrength === 1" class="text-red-400">Lemah</span>
                    <span x-show="passwordStrength === 2" class="text-amber-400">Cukup</span>
                    <span x-show="passwordStrength === 3" class="text-emerald-400">Kuat</span>
                    <span x-show="passwordStrength === 4" class="text-brand-400">Sangat Kuat</span>
                </p>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Konfirmasi Password</label>
                <input type="password" 
                       x-model="confirmPassword"
                       required
                       class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium" 
                       :class="confirmPassword && confirmPassword !== newPassword ? 'border-red-500' : ''"
                       placeholder="Ulangi password baru">
                <p class="text-xs text-red-400 mt-1" x-show="confirmPassword && confirmPassword !== newPassword">
                    Password tidak cocok
                </p>
            </div>

            <button type="submit" 
                    :disabled="isLoading || !newPassword || newPassword !== confirmPassword || passwordStrength < 2"
                    class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                <template x-if="isLoading">
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="isLoading ? 'Menyimpan...' : 'Simpan Password Baru'"></span>
            </button>
        </form>
    </div>
</div>
