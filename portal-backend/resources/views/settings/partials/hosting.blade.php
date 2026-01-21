{{-- Hosting/Deployment Settings Tab --}}
<div x-show="activeTab === 'hosting'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="space-y-6">
        
        {{-- Info Banner --}}
        <div class="bg-gradient-to-r from-violet-500/10 to-purple-500/10 dark:from-violet-500/20 dark:to-purple-500/20 border border-violet-200 dark:border-violet-800/50 rounded-2xl p-4 sm:p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <i data-lucide="cloud-upload" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-violet-900 dark:text-violet-200 mb-1">Konfigurasi Hosting</h3>
                    <p class="text-sm text-violet-700 dark:text-violet-300/80">
                        Isi konfigurasi hosting di sini. Setelah deploy, Anda hanya perlu menyalin konfigurasi ke file <code class="px-1.5 py-0.5 bg-violet-200 dark:bg-violet-800 rounded text-xs font-mono">.env</code> di server.
                    </p>
                </div>
            </div>
        </div>

        {{-- Application Settings Card --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/30 flex-shrink-0">
                    <i data-lucide="globe" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Pengaturan Aplikasi</h2>
                    <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Konfigurasi dasar aplikasi untuk production</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- App URL --}}
                <div class="space-y-2">
                    <label for="hosting_app_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        URL Aplikasi <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <input type="url" name="hosting_app_url" id="hosting_app_url"
                            value="{{ $rawSettings['hosting_app_url'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 pr-12"
                            placeholder="https://yoursite.infinityfree.com">
                        <button type="button" onclick="copyToClipboard('hosting_app_url')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-surface-400 hover:text-violet-500 transition-colors" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- App Name --}}
                <div class="space-y-2">
                    <label for="hosting_app_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nama Aplikasi
                    </label>
                    <input type="text" name="hosting_app_name" id="hosting_app_name"
                        value="{{ $rawSettings['hosting_app_name'] ?? '' }}"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                        placeholder="Portal Berita">
                </div>

                {{-- App Environment --}}
                <div class="space-y-2">
                    <label for="hosting_app_env" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Environment
                    </label>
                    <select name="hosting_app_env" id="hosting_app_env"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200">
                        <option value="production" {{ ($rawSettings['hosting_app_env'] ?? '') == 'production' ? 'selected' : '' }}>Production</option>
                        <option value="staging" {{ ($rawSettings['hosting_app_env'] ?? '') == 'staging' ? 'selected' : '' }}>Staging</option>
                    </select>
                </div>

                {{-- Debug Mode --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Debug Mode
                    </label>
                    <div class="flex items-center gap-3 px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="hosting_app_debug" id="hosting_app_debug" value="true"
                                {{ ($rawSettings['hosting_app_debug'] ?? false) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-surface-300 dark:bg-surface-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-surface-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-500"></div>
                        </label>
                        <span class="text-sm text-surface-600 dark:text-surface-400">Matikan di production!</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Database Settings Card --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 flex-shrink-0">
                    <i data-lucide="database" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Konfigurasi Database</h2>
                    <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pengaturan koneksi database MySQL</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- DB Host --}}
                <div class="space-y-2">
                    <label for="hosting_db_host" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Database Host <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="hosting_db_host" id="hosting_db_host"
                            value="{{ $rawSettings['hosting_db_host'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12"
                            placeholder="sql###.infinityfree.com">
                        <button type="button" onclick="copyToClipboard('hosting_db_host')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-surface-400 hover:text-emerald-500 transition-colors" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- DB Port --}}
                <div class="space-y-2">
                    <label for="hosting_db_port" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Database Port
                    </label>
                    <input type="text" name="hosting_db_port" id="hosting_db_port"
                        value="{{ $rawSettings['hosting_db_port'] ?? '3306' }}"
                        class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                        placeholder="3306">
                </div>

                {{-- DB Name --}}
                <div class="space-y-2">
                    <label for="hosting_db_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Nama Database <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="hosting_db_name" id="hosting_db_name"
                            value="{{ $rawSettings['hosting_db_name'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12"
                            placeholder="if0_12345678_dbname">
                        <button type="button" onclick="copyToClipboard('hosting_db_name')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-surface-400 hover:text-emerald-500 transition-colors" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- DB User --}}
                <div class="space-y-2">
                    <label for="hosting_db_user" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Database User <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="hosting_db_user" id="hosting_db_user"
                            value="{{ $rawSettings['hosting_db_user'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12"
                            placeholder="if0_12345678">
                        <button type="button" onclick="copyToClipboard('hosting_db_user')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-surface-400 hover:text-emerald-500 transition-colors" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- DB Password --}}
                <div class="md:col-span-2 space-y-2" x-data="{ showPassword: false }">
                    <label for="hosting_db_password" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Database Password <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="hosting_db_password" id="hosting_db_password"
                            value="{{ $rawSettings['hosting_db_password'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-24"
                            placeholder="••••••••••••">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1">
                            <button type="button" @click="showPassword = !showPassword" class="p-1.5 text-surface-400 hover:text-emerald-500 transition-colors" title="Tampilkan/Sembunyikan">
                                <i data-lucide="eye" class="w-4 h-4" x-show="!showPassword"></i>
                                <i data-lucide="eye-off" class="w-4 h-4" x-show="showPassword" x-cloak></i>
                            </button>
                            <button type="button" onclick="copyToClipboard('hosting_db_password')" class="p-1.5 text-surface-400 hover:text-emerald-500 transition-colors" title="Salin">
                                <i data-lucide="copy" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-surface-400 flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3 h-3"></i>
                        Password akan dienkripsi sebelum disimpan
                    </p>
                </div>
            </div>
        </div>

        {{-- FTP Settings Card --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30 flex-shrink-0">
                    <i data-lucide="folder-sync" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Konfigurasi FTP</h2>
                    <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pengaturan FTP untuk upload file</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- FTP Host --}}
                <div class="space-y-2">
                    <label for="hosting_ftp_host" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        FTP Host
                    </label>
                    <div class="relative">
                        <input type="text" name="hosting_ftp_host" id="hosting_ftp_host"
                            value="{{ $rawSettings['hosting_ftp_host'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 pr-12"
                            placeholder="ftpupload.net">
                        <button type="button" onclick="copyToClipboard('hosting_ftp_host')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-surface-400 hover:text-amber-500 transition-colors" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- FTP User --}}
                <div class="space-y-2">
                    <label for="hosting_ftp_user" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        FTP User
                    </label>
                    <div class="relative">
                        <input type="text" name="hosting_ftp_user" id="hosting_ftp_user"
                            value="{{ $rawSettings['hosting_ftp_user'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 pr-12"
                            placeholder="if0_12345678">
                        <button type="button" onclick="copyToClipboard('hosting_ftp_user')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-surface-400 hover:text-amber-500 transition-colors" title="Salin">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- FTP Password --}}
                <div class="md:col-span-2 space-y-2" x-data="{ showPassword: false }">
                    <label for="hosting_ftp_password" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        FTP Password
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="hosting_ftp_password" id="hosting_ftp_password"
                            value="{{ $rawSettings['hosting_ftp_password'] ?? '' }}"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 pr-24"
                            placeholder="••••••••••••">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1">
                            <button type="button" @click="showPassword = !showPassword" class="p-1.5 text-surface-400 hover:text-amber-500 transition-colors" title="Tampilkan/Sembunyikan">
                                <i data-lucide="eye" class="w-4 h-4" x-show="!showPassword"></i>
                                <i data-lucide="eye-off" class="w-4 h-4" x-show="showPassword" x-cloak></i>
                            </button>
                            <button type="button" onclick="copyToClipboard('hosting_ftp_password')" class="p-1.5 text-surface-400 hover:text-amber-500 transition-colors" title="Salin">
                                <i data-lucide="copy" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-surface-400 flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3 h-3"></i>
                        Password akan dienkripsi sebelum disimpan
                    </p>
                </div>
            </div>
        </div>

        {{-- Notes & Generate .env Card --}}
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30 flex-shrink-0">
                    <i data-lucide="file-code" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Generate .env</h2>
                    <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Generate konfigurasi siap pakai untuk deployment</p>
                </div>
            </div>

            {{-- Notes --}}
            <div class="space-y-2 mb-6">
                <label for="hosting_notes" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Catatan Deployment
                </label>
                <textarea name="hosting_notes" id="hosting_notes" rows="3"
                    class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-y"
                    placeholder="Catatan tambahan untuk proses deployment...">{{ $rawSettings['hosting_notes'] ?? '' }}</textarea>
            </div>

            {{-- Generate Button --}}
            <button type="button" id="generateEnvBtn"
                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                <i data-lucide="file-down" class="w-5 h-5"></i>
                <span>Generate .env Configuration</span>
            </button>

            {{-- Generated .env Output --}}
            <div id="envOutputContainer" class="hidden mt-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                        Konfigurasi .env
                    </label>
                    <button type="button" onclick="copyEnvConfig()" class="text-sm text-blue-500 hover:text-blue-600 flex items-center gap-1">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                        Salin Semua
                    </button>
                </div>
                <pre id="envOutput" class="w-full p-4 bg-surface-900 dark:bg-surface-950 border border-surface-700 rounded-xl text-sm font-mono text-emerald-400 overflow-x-auto"></pre>
                <p class="mt-2 text-xs text-surface-500">
                    <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                    Salin konfigurasi ini dan tempelkan ke file .env di server hosting Anda
                </p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Copy to clipboard function
    function copyToClipboard(inputId) {
        const input = document.getElementById(inputId);
        const value = input.value;
        
        navigator.clipboard.writeText(value).then(() => {
            // Show toast notification
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Disalin ke clipboard!'
                });
            }
        });
    }

    // Generate .env configuration
    document.getElementById('generateEnvBtn')?.addEventListener('click', function() {
        const appUrl = document.getElementById('hosting_app_url')?.value || '';
        const appName = document.getElementById('hosting_app_name')?.value || 'Laravel';
        const appEnv = document.getElementById('hosting_app_env')?.value || 'production';
        const appDebug = document.getElementById('hosting_app_debug')?.checked ? 'true' : 'false';
        const dbHost = document.getElementById('hosting_db_host')?.value || '';
        const dbPort = document.getElementById('hosting_db_port')?.value || '3306';
        const dbName = document.getElementById('hosting_db_name')?.value || '';
        const dbUser = document.getElementById('hosting_db_user')?.value || '';
        const dbPassword = document.getElementById('hosting_db_password')?.value || '';

        const envConfig = `APP_NAME="${appName}"
APP_ENV=${appEnv}
APP_KEY={{ env('APP_KEY') }}
APP_DEBUG=${appDebug}
APP_TIMEZONE=Asia/Jakarta
APP_URL=${appUrl}

DB_CONNECTION=mysql
DB_HOST=${dbHost}
DB_PORT=${dbPort}
DB_DATABASE=${dbName}
DB_USERNAME=${dbUser}
DB_PASSWORD=${dbPassword}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

CACHE_STORE=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=daily
LOG_LEVEL=error`;

        document.getElementById('envOutput').textContent = envConfig;
        document.getElementById('envOutputContainer').classList.remove('hidden');
        
        // Scroll to output
        document.getElementById('envOutputContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Re-initialize lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Copy entire .env config
    function copyEnvConfig() {
        const envOutput = document.getElementById('envOutput').textContent;
        navigator.clipboard.writeText(envOutput).then(() => {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Konfigurasi .env disalin!'
                });
            }
        });
    }
</script>
@endpush
