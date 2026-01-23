{{-- Email Settings Tab --}}
<div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-500/30 flex-shrink-0">
                <i data-lucide="mail" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Konfigurasi Email</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pengaturan SMTP untuk pengiriman email sistem (OTP, Reset Password, dll)</p>
            </div>
        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6" x-data="{ 
            selectedDriver: '{{ $rawSettings['mail_driver'] ?? 'smtp' }}',
            init() {
                this.$watch('selectedDriver', (value) => {
                    document.getElementById('mail_driver').value = value;
                });
            }
        }">
            {{-- Mail Driver --}}
            <div class="space-y-2">
                <label for="mail_driver" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Driver Email
                </label>
                <select name="mail_driver" id="mail_driver" x-model="selectedDriver"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                    <option value="smtp" {{ ($rawSettings['mail_driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="resend" {{ ($rawSettings['mail_driver'] ?? '') === 'resend' ? 'selected' : '' }}>Resend (HTTP API - Recommended)</option>
                    <option value="sendmail" {{ ($rawSettings['mail_driver'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    <option value="log" {{ ($rawSettings['mail_driver'] ?? '') === 'log' ? 'selected' : '' }}>Log (Development)</option>
                </select>
                <p class="text-xs text-surface-500">Resend menggunakan HTTP API, tidak terpengaruh blokir port SMTP</p>
            </div>

            {{-- Resend API Key (shown when resend is selected) --}}
            <div class="space-y-2" x-show="selectedDriver === 'resend'" x-transition>
                <label for="resend_api_key" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Resend API Key
                </label>
                <div class="relative" x-data="{ showKey: false }">
                    <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input :type="showKey ? 'text' : 'password'" name="resend_api_key" id="resend_api_key"
                        value="{{ $rawSettings['resend_api_key'] ?? '' }}"
                        class="w-full pl-12 pr-12 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="re_xxxxxxxxxx">
                    <button type="button" @click="showKey = !showKey" class="absolute right-4 top-1/2 -translate-y-1/2 text-surface-400 hover:text-surface-600 transition-colors">
                        <i data-lucide="eye" class="w-5 h-5" x-show="!showKey"></i>
                        <i data-lucide="eye-off" class="w-5 h-5" x-show="showKey" x-cloak></i>
                    </button>
                </div>
                <p class="text-xs text-surface-500">Dapatkan di <a href="https://resend.com/api-keys" target="_blank" class="text-primary-500 hover:underline">resend.com/api-keys</a></p>
            </div>

            {{-- SMTP Host (shown when smtp is selected) --}}
            <div class="space-y-2" x-show="selectedDriver === 'smtp'" x-transition>
                <label for="smtp_host" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    SMTP Host
                </label>
                <input type="text" name="smtp_host" id="smtp_host"
                    value="{{ $rawSettings['smtp_host'] ?? '' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                    placeholder="smtp.gmail.com">
            </div>


            {{-- SMTP Port --}}
            <div class="space-y-2" x-show="selectedDriver === 'smtp'" x-transition>
                <label for="smtp_port" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    SMTP Port
                </label>
                <input type="number" name="smtp_port" id="smtp_port"
                    value="{{ $rawSettings['smtp_port'] ?? '587' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                    placeholder="587">
                <p class="text-xs text-surface-500">587 (TLS - Recommended) atau 465 (SSL)</p>
            </div>

            {{-- SMTP Encryption --}}
            <div class="space-y-2" x-show="selectedDriver === 'smtp'" x-transition>
                <label for="smtp_encryption" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Enkripsi
                </label>
                <select name="smtp_encryption" id="smtp_encryption"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                    <option value="tls" {{ ($rawSettings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                    <option value="ssl" {{ ($rawSettings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="" {{ ($rawSettings['smtp_encryption'] ?? '') === '' ? 'selected' : '' }}>None</option>
                </select>
            </div>

            {{-- SMTP Username --}}
            <div class="space-y-2" x-show="selectedDriver === 'smtp'" x-transition>
                <label for="smtp_username" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    SMTP Username
                </label>
                <div class="relative">
                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input type="text" name="smtp_username" id="smtp_username"
                        value="{{ $rawSettings['smtp_username'] ?? '' }}"
                        class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="email@gmail.com">
                </div>
            </div>

            {{-- SMTP Password --}}
            <div class="space-y-2" x-show="selectedDriver === 'smtp'" x-transition x-data="{ showPassword: false }">
                <label for="smtp_password" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    SMTP Password / App Password
                </label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input :type="showPassword ? 'text' : 'password'" name="smtp_password" id="smtp_password"
                        value="{{ $rawSettings['smtp_password'] ?? '' }}"
                        class="w-full pl-12 pr-12 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="••••••••••••••••">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-surface-400 hover:text-surface-600 transition-colors">
                        <i data-lucide="eye" class="w-5 h-5" x-show="!showPassword"></i>
                        <i data-lucide="eye-off" class="w-5 h-5" x-show="showPassword" x-cloak></i>
                    </button>
                </div>
                <p class="text-xs text-surface-500">Untuk Gmail, gunakan App Password 16 karakter</p>
            </div>

            {{-- From Address --}}
            <div class="space-y-2">
                <label for="mail_from_address" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Email Pengirim (From)
                </label>
                <div class="relative">
                    <i data-lucide="at-sign" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input type="email" name="mail_from_address" id="mail_from_address"
                        value="{{ $rawSettings['mail_from_address'] ?? '' }}"
                        class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="noreply@domain.com">
                </div>
            </div>

            {{-- From Name --}}
            <div class="space-y-2">
                <label for="mail_from_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Nama Pengirim
                </label>
                <input type="text" name="mail_from_name" id="mail_from_name"
                    value="{{ $rawSettings['mail_from_name'] ?? '' }}"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                    placeholder="{{ $rawSettings['site_name'] ?? 'Portal' }}">
                <p class="text-xs text-surface-500">Kosongkan untuk menggunakan nama portal</p>
            </div>
        </div>

        {{-- OTP Settings Section --}}
        <div class="mt-8 pt-6 border-t border-surface-200 dark:border-surface-700">
            <h3 class="text-md font-semibold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-5 h-5 text-emerald-500"></i>
                Pengaturan OTP
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- OTP Expiry --}}
                <div class="space-y-2">
                    <label for="otp_expiry_minutes" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Masa Berlaku OTP (menit)
                    </label>
                    <input type="number" name="otp_expiry_minutes" id="otp_expiry_minutes"
                        value="{{ $rawSettings['otp_expiry_minutes'] ?? '10' }}"
                        min="1" max="60"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="10">
                    <p class="text-xs text-surface-500">Rekomendasi: 5-15 menit</p>
                </div>

                {{-- OTP Max Attempts --}}
                <div class="space-y-2">
                    <label for="otp_max_attempts" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Maksimal Percobaan OTP
                    </label>
                    <input type="number" name="otp_max_attempts" id="otp_max_attempts"
                        value="{{ $rawSettings['otp_max_attempts'] ?? '3' }}"
                        min="1" max="10"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="3">
                    <p class="text-xs text-surface-500">Setelah salah sekian kali, OTP harus diminta ulang</p>
                </div>
            </div>
        </div>

        {{-- Test Email Section --}}
        <div class="mt-8 pt-6 border-t border-surface-200 dark:border-surface-700" x-data="{ 
            testEmail: '', 
            isSending: false, 
            testResult: null,
            async sendTestEmail() {
                if (!this.testEmail) {
                    this.testResult = { success: false, message: 'Masukkan email tujuan' };
                    return;
                }
                
                this.isSending = true;
                this.testResult = null;
                
                try {
                    const response = await fetch('{{ route('settings.update.group', 'email-test') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ 
                            test_email: this.testEmail,
                            // Include current form values for testing
                            mail_driver: document.getElementById('mail_driver').value,
                            resend_api_key: document.getElementById('resend_api_key')?.value || '',
                            smtp_host: document.getElementById('smtp_host')?.value || '',
                            smtp_port: document.getElementById('smtp_port')?.value || '587',
                            smtp_username: document.getElementById('smtp_username')?.value || '',
                            smtp_password: document.getElementById('smtp_password')?.value || '',
                            smtp_encryption: document.getElementById('smtp_encryption')?.value || 'tls',
                            mail_from_address: document.getElementById('mail_from_address')?.value || '',
                            mail_from_name: document.getElementById('mail_from_name')?.value || '',
                        })
                    });
                    
                    this.testResult = await response.json();
                } catch (error) {
                    this.testResult = { success: false, message: 'Gagal mengirim email test: ' + error.message };
                } finally {
                    this.isSending = false;
                }
            }
        }">
            <h3 class="text-md font-semibold text-surface-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="send" class="w-5 h-5 text-blue-500"></i>
                Test Pengiriman Email
            </h3>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"></i>
                    <input type="email" x-model="testEmail"
                        class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                        placeholder="Masukkan email untuk test">
                </div>
                <button type="button" @click="sendTestEmail()" 
                    :disabled="isSending"
                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                    <template x-if="isSending">
                        <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <i data-lucide="send" class="w-4 h-4" x-show="!isSending"></i>
                    <span x-text="isSending ? 'Mengirim...' : 'Kirim Test'"></span>
                </button>
            </div>

            {{-- Test Result --}}
            <div x-show="testResult" x-cloak class="mt-4">
                <div class="p-4 rounded-xl flex items-start gap-3"
                     :class="testResult?.success ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30'">
                    <i :data-lucide="testResult?.success ? 'check-circle' : 'x-circle'" 
                       :class="testResult?.success ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" 
                       class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm" :class="testResult?.success ? 'text-emerald-800 dark:text-emerald-300' : 'text-red-800 dark:text-red-300'" x-text="testResult?.message"></p>
                </div>
            </div>
        </div>
    </div>
</div>
