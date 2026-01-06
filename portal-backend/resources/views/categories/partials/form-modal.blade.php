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
                            <h3 class="text-lg font-bold text-white" x-text="formMode === 'create' ? 'Tambah Kategori Baru' : 'Edit Kategori'"></h3>
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
                            Nama Kategori <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text"
                            x-model="formData.name"
                            @input="generateSlug()"
                            placeholder="Masukkan nama kategori"
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
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400 text-sm">/kategori/</span>
                            <input 
                                type="text"
                                x-model="formData.slug"
                                placeholder="url-kategori"
                                class="w-full pl-24 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
                                :class="formErrors.slug ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
                            >
                        </div>
                        <p class="mt-1 text-xs text-surface-500">Dibuat otomatis dari nama jika dikosongkan</p>
                        <template x-if="formErrors.slug">
                            <p class="mt-1 text-xs text-rose-500" x-text="formErrors.slug[0]"></p>
                        </template>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Deskripsi
                        </label>
                        <textarea 
                            x-model="formData.description"
                            placeholder="Deskripsi kategori (opsional)"
                            rows="3"
                            class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- Color & Icon Row --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Color --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Warna
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="color in colorOptions" :key="color">
                                    <button 
                                        type="button"
                                        @click="formData.color = color"
                                        class="w-8 h-8 rounded-lg transition-transform hover:scale-110"
                                        :style="`background-color: ${color}`"
                                        :class="formData.color === color ? 'ring-2 ring-offset-2 ring-theme-500 scale-110' : ''"
                                    ></button>
                                </template>
                            </div>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Urutan
                            </label>
                            <input 
                                type="number"
                                x-model="formData.sort_order"
                                min="0"
                                class="w-full px-4 py-3 bg-surface-50 dark:bg-surface-800 border-2 border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-0 focus:border-theme-500 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Icon Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                            Ikon
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="icon in iconOptions" :key="icon">
                                <button 
                                    type="button"
                                    @click="formData.icon = icon; $nextTick(() => lucide.createIcons())"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all"
                                    :class="formData.icon === icon 
                                        ? 'bg-theme-500 text-white shadow-lg shadow-theme-500/30' 
                                        : 'bg-surface-100 dark:bg-surface-800 text-surface-600 dark:text-surface-400 hover:bg-surface-200 dark:hover:bg-surface-700'"
                                >
                                    <i :data-lucide="icon" class="w-5 h-5"></i>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Is Active Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-800 rounded-xl">
                        <div>
                            <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Status Aktif</p>
                            <p class="text-xs text-surface-500">Kategori akan ditampilkan jika aktif</p>
                        </div>
                        <button 
                            type="button"
                            @click="formData.is_active = !formData.is_active"
                            :class="formData.is_active ? 'bg-theme-500' : 'bg-surface-300 dark:bg-surface-600'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-theme-500 focus:ring-offset-2"
                        >
                            <span 
                                :class="formData.is_active ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            ></span>
                        </button>
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
