{{-- Tab 1: Content (Title, Slug, Excerpt, Editor) --}}
<div x-show="activeTab === 'content'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 sm:space-y-8">
    
    {{-- Modern Title Input --}}
    <div class="group space-y-3 sm:space-y-4">
        <div class="relative">
            <input 
                type="text"
                x-model="formData.title"
                @input="generateSlug(); formErrors.title = null"
                placeholder="Tulis Judul Berita..."
                :class="{
                    'border-rose-500 focus:border-rose-500': formErrors.title,
                    'border-emerald-500': formData.title && formData.title.length >= 3 && !formErrors.title,
                    'border-surface-100 dark:border-surface-800 focus:border-theme-500': !formData.title || formData.title.length < 3
                }"
                class="w-full px-0 py-2 sm:py-4 bg-transparent border-0 border-b-2 text-2xl sm:text-4xl font-black text-surface-900 dark:text-white placeholder-surface-300/50 focus:ring-0 transition-all duration-300"
            >
            {{-- Character counter --}}
            <div class="absolute right-0 bottom-3 text-[10px] text-surface-400" x-show="formData.title">
                <span x-text="formData.title.length"></span>/255
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 px-3 py-2 bg-surface-50 dark:bg-surface-800/50 rounded-lg group-focus-within:bg-theme-50 dark:group-focus-within:bg-theme-900/10 transition-colors duration-300 overflow-hidden">
            <i data-lucide="link" class="w-3 h-3 text-surface-400 group-focus-within:text-theme-500 shrink-0"></i>
            <span class="text-[10px] sm:text-xs text-surface-400 shrink-0">{{ parse_url(config('app.url', 'example.com'), PHP_URL_HOST) }}/berita/</span>
            <input 
                type="text" 
                x-model="formData.slug" 
                class="bg-transparent border-0 p-0 text-[10px] sm:text-xs text-surface-500 font-mono focus:ring-0 focus:text-theme-600 dark:focus:text-theme-400 w-full min-w-[50px]"
                placeholder="slug-auto"
            >
        </div>
        <template x-if="formErrors.title">
            <p class="text-sm font-medium text-rose-500 flex items-center gap-2 animate-pulse">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span x-text="formErrors.title[0]"></span>
            </p>
        </template>
    </div>

    {{-- Summary --}}
    <div class="space-y-2">
        <label class="text-sm font-bold text-surface-700 dark:text-surface-300">Ringkasan Berita</label>
        <textarea 
            x-model="formData.excerpt"
            rows="3"
            class="w-full px-4 sm:px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-0 ring-1 ring-surface-200 dark:ring-surface-800 rounded-2xl text-sm focus:ring-2 focus:ring-theme-500 transition-all resize-none dark:text-white placeholder-surface-400"
            placeholder="Tulis ringkasan singkat..."
        ></textarea>
    </div>

    {{-- Editor --}}
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <label class="text-sm font-bold text-surface-700 dark:text-surface-300 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-theme-500"></i>
                Editor Konten
            </label>
            
            {{-- Security Status Indicator --}}
            <div x-show="formData.content" class="flex items-center gap-2 self-start sm:self-auto">
                {{-- Safe Indicator --}}
                <template x-if="!injectionDetected && formData.content.length > 50">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Konten Aman</span>
                    </div>
                </template>
                
                {{-- Threat Detected Indicator --}}
                <template x-if="injectionDetected">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 animate-pulse">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-500"></i>
                        <span class="text-xs font-bold text-rose-600 dark:text-rose-400" x-text="detectedThreats.length + ' Ancaman'"></span>
                    </div>
                </template>
            </div>
        </div>
        
        {{-- Security Alert Panel --}}
        @include('articles.partials.form-modal.security-alert')

        {{-- Trix Editor Toolbar & Content --}}
        @include('articles.partials.form-modal.trix-editor')
        
        <template x-if="formErrors.content">
            <p class="text-xs text-rose-500 mt-1 pl-2" x-text="formErrors.content[0]"></p>
        </template>
    </div>
</div>
