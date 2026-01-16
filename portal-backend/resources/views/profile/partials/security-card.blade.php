{{-- Security Settings Card --}}
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-rose to-pink-500 flex items-center justify-center shadow-lg shadow-accent-rose/30 flex-shrink-0">
                <i data-lucide="shield-check" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Keamanan Akun</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Kelola password dan keamanan</p>
            </div>
        </div>
        <button type="button" 
                @click="showPasswordForm = !showPasswordForm"
                class="flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
            <i data-lucide="key" class="w-4 h-4"></i>
            <span>Ubah Password</span>
        </button>
    </div>

    {{-- Security Info --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        {{-- Last Login --}}
        <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-accent-emerald/20 flex items-center justify-center">
                    <i data-lucide="log-in" class="w-5 h-5 text-accent-emerald"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Login Terakhir</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400">
                        @if($user->last_login_at)
                            {{ $user->last_login_at->diffForHumans() }}
                        @else
                            Tidak tersedia
                        @endif
                    </p>
                </div>
            </div>
            @if($user->last_login_ip)
            <div class="mt-2 text-xs text-surface-500 dark:text-surface-400">
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="globe" class="w-3 h-3"></i>
                    IP: {{ $user->last_login_ip }}
                </span>
            </div>
            @endif
        </div>

        {{-- Account Status --}}
        <div class="p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5 text-primary-600 dark:text-primary-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Status Akun</p>
                    <p class="text-xs text-accent-emerald font-medium">Aktif & Terverifikasi</p>
                </div>
            </div>
            <div class="mt-2 text-xs text-surface-500 dark:text-surface-400">
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="shield" class="w-3 h-3"></i>
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Change Password Form --}}
    <div x-show="showPasswordForm" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="border-t border-surface-200 dark:border-surface-700 pt-6">
        <form id="passwordForm" @submit.prevent="updatePassword()">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Current Password --}}
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                        Password Saat Ini <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-surface-400"></i>
                        </div>
                        <input :type="showCurrentPassword ? 'text' : 'password'" 
                               x-model="passwordData.current_password"
                               class="w-full pl-12 pr-12 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                               placeholder="••••••••">
                        <button type="button" 
                                @click="showCurrentPassword = !showCurrentPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-surface-400 hover:text-surface-600">
                            <i :data-lucide="showCurrentPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                        Password Baru <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="key" class="w-5 h-5 text-surface-400"></i>
                        </div>
                        <input :type="showNewPassword ? 'text' : 'password'" 
                               x-model="passwordData.password"
                               class="w-full pl-12 pr-12 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                               placeholder="••••••••">
                        <button type="button" 
                                @click="showNewPassword = !showNewPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-surface-400 hover:text-surface-600">
                            <i :data-lucide="showNewPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                        Konfirmasi Password <span class="text-accent-rose">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="check-circle" class="w-5 h-5 text-surface-400"></i>
                        </div>
                        <input :type="showConfirmPassword ? 'text' : 'password'" 
                               x-model="passwordData.password_confirmation"
                               class="w-full pl-12 pr-12 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                               placeholder="••••••••">
                        <button type="button" 
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-surface-400 hover:text-surface-600">
                            <i :data-lucide="showConfirmPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Password Requirements --}}
            <div class="mt-4 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl">
                <p class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Persyaratan Password:</p>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-surface-500 dark:text-surface-400">
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-3 h-3 text-accent-emerald"></i>
                        Minimal 8 karakter
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-3 h-3 text-accent-emerald"></i>
                        Huruf besar dan kecil
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-3 h-3 text-accent-emerald"></i>
                        Minimal 1 angka
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-3 h-3 text-accent-emerald"></i>
                        Karakter yang kuat
                    </li>
                </ul>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                <button type="button" 
                        @click="showPasswordForm = false; resetPasswordForm()"
                        class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200 text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit"
                        class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-gradient-to-r from-accent-rose to-pink-500 text-white rounded-xl font-semibold shadow-lg shadow-accent-rose/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                    <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    {{-- Logout All Devices Section --}}
    <div class="border-t border-surface-200 dark:border-surface-700 pt-6 mt-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <i data-lucide="log-out" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-surface-900 dark:text-white">Keluar dari Semua Perangkat</h3>
                        <p class="text-xs text-surface-500 dark:text-surface-400">Logout dari semua sesi di perangkat lain</p>
                    </div>
                </div>
            </div>
            <button type="button" 
                    @click="showLogoutAllModal = true"
                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl font-medium hover:bg-amber-200 dark:hover:bg-amber-800/40 transition-all duration-200 flex-shrink-0">
                <i data-lucide="shield-alert" class="w-4 h-4"></i>
                <span>Keluar dari Semua</span>
            </button>
        </div>
    </div>
</div>

{{-- Logout All Devices Modal - Placed outside card for proper fullscreen overlay --}}
<template x-teleport="body">
    <div x-show="showLogoutAllModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-[9999] overflow-y-auto"
         @keydown.escape.window="showLogoutAllModal = false">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showLogoutAllModal = false"></div>
        
        {{-- Modal Content --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-surface-900 rounded-2xl shadow-2xl w-full max-w-md mx-auto"
                 x-show="showLogoutAllModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>
                
                {{-- Modal Header --}}
                <div class="p-6 border-b border-surface-200 dark:border-surface-700">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <i data-lucide="shield-alert" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white">Konfirmasi Keamanan</h3>
                            <p class="text-sm text-surface-500 dark:text-surface-400">Keluar dari semua perangkat lain</p>
                        </div>
                    </div>
                </div>
                
                {{-- Modal Body --}}
                <form @submit.prevent="logoutAllDevices()">
                    <div class="p-6">
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl mb-4">
                            <p class="text-sm text-amber-700 dark:text-amber-300">
                                <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                                Tindakan ini akan mengeluarkan Anda dari semua perangkat lain dan membatalkan semua token "Ingat Saya".
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Masukkan Password untuk Konfirmasi <span class="text-accent-rose">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-surface-400"></i>
                                </div>
                                <input :type="showLogoutPassword ? 'text' : 'password'" 
                                       x-model="logoutAllPassword"
                                       class="w-full pl-12 pr-12 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                       placeholder="••••••••"
                                       required>
                                <button type="button" 
                                        @click="showLogoutPassword = !showLogoutPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-surface-400 hover:text-surface-600">
                                    <i :data-lucide="showLogoutPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Modal Footer --}}
                    <div class="p-6 border-t border-surface-200 dark:border-surface-700 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                        <button type="button" 
                                @click="showLogoutAllModal = false; logoutAllPassword = ''"
                                class="w-full sm:w-auto px-5 py-2.5 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                                :disabled="isLoggingOutAll"
                                class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span x-show="!isLoggingOutAll" class="flex items-center gap-2">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Keluar dari Semua
                            </span>
                            <span x-show="isLoggingOutAll" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

