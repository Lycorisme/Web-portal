{{-- Login Form Partial --}}
<div x-show="mode === 'login'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-data="loginFormHandler()">
    
    {{-- Include Lockout Countdown Modal --}}
    @include('auth.partials.lockout-modal')
    
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-400">Masuk untuk mengakses dashboard Anda.</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- Honeypot Field (Hidden Anti-Bot Protection) --}}
        <div style="position: absolute; left: -9999px; opacity: 0; height: 0; overflow: hidden;" aria-hidden="true">
            <label for="website_url">Website (leave empty)</label>
            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
        </div>

        {{-- Remaining Attempts Warning (only when NOT locked) --}}
        @php
            $remaining = request()->query('remaining');
            $lockoutUntil = request()->query('lockout_until');
        @endphp
        
        <template x-if="!isLocked && remainingAttempts !== null && remainingAttempts <= 3 && remainingAttempts > 0">
            <div class="p-4 rounded-xl bg-amber-500/20 border border-amber-500/30 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-500/30 rounded-lg shrink-0">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-300">Peringatan Keamanan</p>
                        <p class="text-xs text-amber-400/80 mt-0.5">
                            Sisa <span class="font-bold" x-text="remainingAttempts"></span> percobaan login sebelum akun dikunci sementara.
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-2">
            <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Identity</label>
            <input type="email" 
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   :disabled="isLocked"
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium @error('email') border-red-500 @enderror disabled:opacity-50 disabled:cursor-not-allowed" 
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
                   :disabled="isLocked"
                   class="w-full px-5 py-4 rounded-xl modern-input text-white placeholder-slate-500 text-sm font-medium tracking-widest @error('password') border-red-500 @enderror disabled:opacity-50 disabled:cursor-not-allowed" 
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

        <button type="submit" 
                :disabled="isLocked"
                class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 text-white font-bold text-sm tracking-widest shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 uppercase mt-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
            <span x-show="!isLocked">Masuk Portal</span>
            <span x-show="isLocked" class="flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Menunggu...
            </span>
        </button>
    </form>
</div>

<script>
function loginFormHandler() {
    return {
        isLocked: false,
        countdown: 0,
        totalSeconds: 0,
        remainingAttempts: null,
        interval: null,
        progressPercent: 100,
        
        init() {
            // Check for lockout_until parameter
            const urlParams = new URLSearchParams(window.location.search);
            const lockoutUntil = urlParams.get('lockout_until');
            const remaining = urlParams.get('remaining');
            
            if (remaining !== null) {
                this.remainingAttempts = parseInt(remaining);
            }
            
            if (lockoutUntil) {
                const lockoutTime = parseInt(lockoutUntil) * 1000; // Convert to milliseconds
                const now = Date.now();
                const diff = Math.floor((lockoutTime - now) / 1000);
                
                if (diff > 0) {
                    this.totalSeconds = diff;
                    this.countdown = diff;
                    this.isLocked = true;
                    this.startCountdown();
                    
                    // Clean URL without refresh
                    const cleanUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            }
            
            // Also check localStorage for persistent lockout
            this.checkStoredLockout();
        },
        
        checkStoredLockout() {
            const stored = localStorage.getItem('login_lockout_until');
            if (stored) {
                const lockoutTime = parseInt(stored);
                const now = Date.now();
                const diff = Math.floor((lockoutTime - now) / 1000);
                
                if (diff > 0 && !this.isLocked) {
                    this.totalSeconds = diff;
                    this.countdown = diff;
                    this.isLocked = true;
                    this.startCountdown();
                } else if (diff <= 0) {
                    localStorage.removeItem('login_lockout_until');
                }
            }
        },
        
        startCountdown() {
            // Store lockout in localStorage for persistence
            const lockoutTime = Date.now() + (this.countdown * 1000);
            localStorage.setItem('login_lockout_until', lockoutTime.toString());
            
            // Clear any existing interval
            if (this.interval) {
                clearInterval(this.interval);
            }
            
            this.interval = setInterval(() => {
                this.countdown--;
                this.progressPercent = (this.countdown / this.totalSeconds) * 100;
                
                if (this.countdown <= 0) {
                    this.unlockAccount();
                }
            }, 1000);
        },
        
        unlockAccount() {
            clearInterval(this.interval);
            this.isLocked = false;
            this.countdown = 0;
            this.progressPercent = 100;
            localStorage.removeItem('login_lockout_until');
            
            // Clear remaining attempts indicator
            this.remainingAttempts = null;
        }
    };
}
</script>
