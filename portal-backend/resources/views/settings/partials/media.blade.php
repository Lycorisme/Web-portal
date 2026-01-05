{{-- Media Settings Tab --}}
<div x-show="activeTab === 'media'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-teal-500 to-accent-cyan flex items-center justify-center shadow-lg shadow-teal-500/30 flex-shrink-0">
                <i data-lucide="image" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Pengaturan Media</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Logo, favicon, dan aset visual lainnya</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
            {{-- Logo --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Logo Utama
                </label>
                <div class="relative group">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['logo_url']))
                            <img src="{{ $rawSettings['logo_url'] }}" alt="Logo" class="max-h-full max-w-full object-contain p-4">
                        @else
                            <i data-lucide="upload-cloud" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload logo</p>
                            <p class="text-xs text-surface-400">PNG, JPG, SVG (max 2MB)</p>
                        @endif
                    </div>
                    <input type="file" name="logo_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="logo_url_current" value="{{ $rawSettings['logo_url'] ?? '' }}">
                </div>
            </div>

            {{-- Favicon --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Favicon
                </label>
                <div class="relative group">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['favicon_url']))
                            <img src="{{ $rawSettings['favicon_url'] }}" alt="Favicon" class="w-16 h-16 object-contain">
                        @else
                            <i data-lucide="bookmark" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload favicon</p>
                            <p class="text-xs text-surface-400">ICO, PNG (32x32 atau 64x64)</p>
                        @endif
                    </div>
                    <input type="file" name="favicon_url" accept="image/*,.ico" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="favicon_url_current" value="{{ $rawSettings['favicon_url'] ?? '' }}">
                </div>
            </div>

            {{-- Letterhead --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Kop Surat
                </label>
                <div class="relative group">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['letterhead_url']))
                            <img src="{{ $rawSettings['letterhead_url'] }}" alt="Kop Surat" class="max-h-full max-w-full object-contain p-4">
                        @else
                            <i data-lucide="file-text" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload kop surat</p>
                            <p class="text-xs text-surface-400">PNG, JPG (max 2MB)</p>
                        @endif
                    </div>
                    <input type="file" name="letterhead_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="letterhead_url_current" value="{{ $rawSettings['letterhead_url'] ?? '' }}">
                </div>
            </div>

            {{-- Signature --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Tanda Tangan Digital
                </label>
                <div class="relative group">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['signature_url']))
                            <img src="{{ $rawSettings['signature_url'] }}" alt="Tanda Tangan" class="max-h-full max-w-full object-contain p-4">
                        @else
                            <i data-lucide="pen-tool" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload tanda tangan</p>
                            <p class="text-xs text-surface-400">PNG dengan background transparan</p>
                        @endif
                    </div>
                    <input type="file" name="signature_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="signature_url_current" value="{{ $rawSettings['signature_url'] ?? '' }}">
                </div>
            </div>

            {{-- Stamp --}}
            <div class="space-y-4 sm:col-span-2">
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Stempel
                </label>
                <div class="relative group sm:max-w-md">
                    <div class="w-full h-40 border-2 border-dashed border-surface-300 dark:border-surface-700 rounded-2xl flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:border-primary-500 transition-colors cursor-pointer overflow-hidden">
                        @if(!empty($rawSettings['stamp_url']))
                            <img src="{{ $rawSettings['stamp_url'] }}" alt="Stempel" class="max-h-full max-w-full object-contain p-4">
                        @else
                            <i data-lucide="stamp" class="w-12 h-12 text-surface-400 mb-2"></i>
                            <p class="text-sm text-surface-500">Klik untuk upload stempel</p>
                            <p class="text-xs text-surface-400">PNG dengan background transparan</p>
                        @endif
                    </div>
                    <input type="file" name="stamp_url" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <input type="hidden" name="stamp_url_current" value="{{ $rawSettings['stamp_url'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
