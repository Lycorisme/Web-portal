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
        <button type="button" 
                @click="editMode = !editMode"
                class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-medium transition-all duration-200"
                :class="editMode 
                    ? 'bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300' 
                    : 'bg-theme-gradient text-white shadow-theme hover:shadow-xl hover:-translate-y-0.5'">
            <i :data-lucide="editMode ? 'x' : 'edit-3'" class="w-4 h-4"></i>
            <span x-text="editMode ? 'Batal' : 'Edit Profil'"></span>
        </button>
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
                           :disabled="!editMode"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                           :disabled="!editMode"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                           :disabled="!editMode"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                           :disabled="!editMode"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                           :disabled="!editMode"
                           class="w-full pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                              :disabled="!editMode"
                              rows="4"
                              maxlength="500"
                              class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white placeholder-surface-400 focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all duration-200 resize-none disabled:opacity-60 disabled:cursor-not-allowed"
                              placeholder="Ceritakan sedikit tentang diri Anda..."></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-surface-400">
                        <span x-text="formData.bio?.length || 0"></span>/500
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div x-show="editMode" x-transition class="mt-6 flex flex-col-reverse sm:flex-row gap-3 sm:gap-4 sm:justify-end">
            <button type="button" 
                    @click="resetForm()"
                    class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl font-medium hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200 text-sm sm:text-base">
                <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-2"></i>
                Reset
            </button>
            <button type="submit"
                    class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-theme-gradient text-white rounded-xl font-semibold shadow-theme hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
