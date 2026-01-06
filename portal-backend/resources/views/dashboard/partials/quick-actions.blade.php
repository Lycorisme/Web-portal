<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm">
    <h3 class="font-bold text-surface-900 dark:text-white mb-4">Aksi Cepat</h3>
     <div class="grid grid-cols-2 gap-3">
        <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="pen-tool" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Tulis Berita</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="image" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Galeri</span>
        </a>
        <a href="{{ route('settings') }}" wire:navigate class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Pengaturan</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-800 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors group text-center">
             <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-surface-700 dark:text-surface-300">Laporan</span>
        </a>
    </div>
</div>
