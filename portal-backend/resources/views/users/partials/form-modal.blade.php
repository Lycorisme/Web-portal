{{-- Form Modal (Create/Edit) --}}
<template x-teleport="body">
    <div 
        x-show="showFormModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showFormModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="closeFormModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div 
                x-show="showFormModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <i :data-lucide="formMode === 'create' ? 'user-plus' : 'user-cog'" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white" x-text="formMode === 'create' ? 'Tambah User Baru' : 'Edit User'"></h3>
                        </div>
                        <button @click="closeFormModal()" class="p-1.5 rounded-lg hover:bg-white/20 transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>

                {{-- Form Content --}}
                <form @submit.prevent="submitForm()" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    {{-- Profile Photo Preview --}}
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg overflow-hidden bg-gradient-to-br from-theme-400 to-theme-600">
                            <template x-if="formData.profile_photo_preview">
                                <img :src="formData.profile_photo_preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!formData.profile_photo_preview">
                                <span class="text-white font-bold text-xl" x-text="formData.name ? formData.name.charAt(0).toUpperCase() : 'U'"></span>
                            </template>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Foto Profil
                            </label>
                            <input 
                                type="file"
                                @change="handlePhotoUpload($event)"
                                accept="image/*"
                                class="w-full text-sm text-surface-600 dark:text-surface-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-theme-50 file:text-theme-600 dark:file:bg-theme-900/30 dark:file:text-theme-400 hover:file:bg-theme-100 dark:hover:file:bg-theme-900/50 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Name & Email Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text"
                                x-model="formData.name"
                                placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.name ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                            <template x-if="formErrors.name">
                                <p class="mt-1 text-xs text-rose-500" x-text="formErrors.name[0]"></p>
                            </template>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="email"
                                x-model="formData.email"
                                placeholder="email@example.com"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.email ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                            <template x-if="formErrors.email">
                                <p class="mt-1 text-xs text-rose-500" x-text="formErrors.email[0]"></p>
                            </template>
                        </div>
                    </div>

                    {{-- Password & Confirmation Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Password <span class="text-rose-500" x-show="formMode === 'create'">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPassword ? 'text' : 'password'"
                                    x-model="formData.password"
                                    :placeholder="formMode === 'create' ? 'Minimal 8 karakter' : 'Kosongkan jika tidak diubah'"
                                    class="w-full px-4 py-3 pr-12 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                    :class="formErrors.password ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                                >
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 hover:bg-surface-200 dark:hover:bg-surface-700 rounded-lg transition-colors"
                                >
                                    <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4 text-surface-400"></i>
                                </button>
                            </div>
                            <template x-if="formErrors.password">
                                <p class="mt-1 text-xs text-rose-500" x-text="formErrors.password[0]"></p>
                            </template>
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Konfirmasi Password
                            </label>
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                x-model="formData.password_confirmation"
                                placeholder="Ulangi password"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Role & Position Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Role <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                x-model="formData.role"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.role ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                                <option value="author">Penulis</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                            <template x-if="formErrors.role">
                                <p class="mt-1 text-xs text-rose-500" x-text="formErrors.role[0]"></p>
                            </template>
                        </div>

                        {{-- Position --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Jabatan
                            </label>
                            <input 
                                type="text"
                                x-model="formData.position"
                                placeholder="Contoh: Editor"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Phone & Location Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Telepon
                            </label>
                            <input 
                                type="text"
                                x-model="formData.phone"
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>

                        {{-- Location --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Lokasi
                            </label>
                            <input 
                                type="text"
                                x-model="formData.location"
                                placeholder="Contoh: Jakarta"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Bio
                        </label>
                        <textarea 
                            x-model="formData.bio"
                            placeholder="Deskripsi singkat tentang user (opsional)"
                            rows="3"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-surface-200 dark:border-surface-700">
                        <button 
                            type="button"
                            @click="closeFormModal()"
                            class="flex-1 px-4 py-3 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 font-medium rounded-xl hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            :disabled="formLoading"
                            class="flex-1 px-4 py-3 bg-theme-gradient text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <template x-if="formLoading">
                                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </template>
                            <span x-text="formMode === 'create' ? 'Simpan' : 'Update'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
