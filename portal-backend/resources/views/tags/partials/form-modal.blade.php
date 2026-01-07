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
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.stop
            >
                {{-- Header --}}
                <div class="bg-gradient-to-r from-theme-500 to-theme-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <i :data-lucide="formMode === 'create' ? 'plus' : 'pencil'" class="w-5 h-5 text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white" x-text="formMode === 'create' ? 'Tambah Tag Baru' : 'Edit Tag'"></h3>
                        </div>
                        <button @click="closeFormModal()" class="p-1.5 rounded-lg hover:bg-white/20 transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-white"></i>
                        </button>
                    </div>
                </div>

                {{-- Form Content --}}
                <form @submit.prevent="submitForm()" class="p-6 space-y-5">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Nama Tag <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text"
                            x-model="formData.name"
                            @input="generateSlug()"
                            placeholder="Masukkan nama tag"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                            :class="formErrors.name ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                        >
                        <template x-if="formErrors.name">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.name[0]"></p>
                        </template>
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Slug
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400 text-sm">/tag/</span>
                            <input 
                                type="text"
                                x-model="formData.slug"
                                placeholder="url-tag"
                                class="w-full pl-14 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.slug ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                        </div>
                        <p class="mt-1 text-xs text-surface-500">Dibuat otomatis dari nama jika dikosongkan</p>
                        <template x-if="formErrors.slug">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.slug[0]"></p>
                        </template>
                    </div>

                    {{-- Is Active Toggle --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Status Tag
                        </label>
                        <div class="flex items-center gap-3 p-3 bg-surface-50 dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700">
                            <button 
                                type="button" 
                                @click="formData.is_active = !formData.is_active"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="formData.is_active ? 'bg-theme-500' : 'bg-surface-300 dark:bg-surface-600'"
                            >
                                <span 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="formData.is_active ? 'translate-x-5' : 'translate-x-0'"
                                ></span>
                            </button>
                            <span class="text-sm font-medium" :class="formData.is_active ? 'text-theme-600 dark:text-theme-400' : 'text-surface-500'">
                                <span x-text="formData.is_active ? 'Aktif' : 'Nonaktif'"></span>
                            </span>
                        </div>
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
