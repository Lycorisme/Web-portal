{{-- Profile Information Card --}}
<div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/30 flex-shrink-0">
                <i data-lucide="user-circle" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Informasi Profil</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Kelola informasi akun Anda</p>
            </div>
        </div>
        {{-- Change indicator --}}
        <div x-show="hasChanges" x-cloak class="flex items-center gap-2 px-3 py-1.5 bg-amber-100 dark:bg-amber-900/30 rounded-full">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
            <span class="text-xs font-medium text-amber-700 dark:text-amber-400">Perubahan belum disimpan</span>
        </div>
    </div>

    <form id="profileInfoForm" @submit.prevent="saveProfileInfo()">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            {{-- Full Name --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                    Nama Lengkap <span class="text-accent-rose">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <input type="text" 
                           name="name" 
                           x-model="formData.name"
                           @input="checkForChanges()"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                           placeholder="Masukkan nama lengkap">
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                    Email <span class="text-accent-rose">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           x-model="formData.email"
                           @input="checkForChanges()"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                           placeholder="email@example.com">
                </div>
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                    Nomor Telepon
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="phone" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <input type="tel" 
                           name="phone" 
                           x-model="formData.phone"
                           @input="checkForChanges()"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                           placeholder="+62 812 3456 7890">
                </div>
            </div>

            {{-- Position --}}
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                    Jabatan
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="briefcase" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <input type="text" 
                           name="position" 
                           x-model="formData.position"
                           @input="checkForChanges()"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                           placeholder="Jabatan Anda">
                </div>
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                    Lokasi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="map-pin" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <input type="text" 
                           name="location" 
                           x-model="formData.location"
                           @input="checkForChanges()"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200"
                           placeholder="Kota, Provinsi">
                </div>
            </div>

            {{-- Bio --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                    Bio
                </label>
                <div class="relative">
                    <textarea name="bio" 
                              x-model="formData.bio"
                              @input="checkForChanges()"
                              rows="4"
                              maxlength="500"
                              class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 resize-none"
                              placeholder="Ceritakan sedikit tentang diri Anda..."></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-surface-400">
                        <span x-text="formData.bio?.length || 0"></span>/500
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
