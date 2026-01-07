{{-- Auto-Sanitization Preview Modal --}}
<template x-teleport="body">
    <div x-show="showSanitizePreview" x-cloak class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="showSanitizePreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm" @click="closeSanitizePreview()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showSanitizePreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white dark:bg-surface-900 shadow-2xl w-full max-w-4xl rounded-3xl" @click.stop>
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-white/20 rounded-xl"><i data-lucide="sparkles" class="w-6 h-6 text-white"></i></div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Preview Auto-Sanitization</h3>
                            <p class="text-sm text-white/80">Lihat hasil pembersihan konten</p>
                        </div>
                    </div>
                    <button type="button" @click="closeSanitizePreview()" class="p-2 hover:bg-white/20 rounded-lg"><i data-lucide="x" class="w-5 h-5 text-white"></i></button>
                </div>
                <div class="px-6 py-3 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 flex items-center gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-amber-600 flex-shrink-0"></i>
                    <p class="text-sm text-amber-700 dark:text-amber-300">Teks dengan <span class="bg-rose-200 dark:bg-rose-800 text-rose-600 px-1.5 py-0.5 rounded text-xs font-mono line-through">[REMOVED]</span> akan dihapus.</p>
                </div>
                <div class="px-6 py-6 max-h-[60vh] overflow-y-auto">
                    <div class="prose prose-sm dark:prose-invert max-w-none p-4 bg-surface-50 dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700" x-html="sanitizedPreviewContent"></div>
                </div>
                <div class="px-6 py-4 bg-white dark:bg-surface-900 border-t flex gap-3 justify-end">
                    <button type="button" @click="closeSanitizePreview()" class="px-5 py-2.5 text-sm font-semibold text-surface-600 hover:bg-surface-100 rounded-xl">Batal</button>
                    <button type="button" @click="applySanitization()" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white font-bold rounded-xl shadow-lg flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>Terapkan Pembersihan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
