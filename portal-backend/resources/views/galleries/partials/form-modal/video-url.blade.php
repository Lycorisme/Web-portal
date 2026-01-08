{{-- Video URL (for video type) --}}
<div x-show="formData.media_type === 'video'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="bg-surface-50 dark:bg-surface-800/30 rounded-2xl p-4 sm:p-6 border border-surface-100 dark:border-surface-700/50 space-y-4">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-3 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-2xl mb-2">
                <i data-lucide="youtube" class="w-6 h-6"></i>
            </div>
            <h4 class="text-base font-bold text-surface-900 dark:text-white">Link Video YouTube</h4>
            <p class="text-xs text-surface-500">Masukkan URL video dari YouTube</p>
        </div>
        <input 
            type="url"
            x-model="formData.video_url"
            placeholder="https://youtube.com/watch?v=... atau https://youtu.be/..."
            class="w-full px-4 py-3 bg-white dark:bg-surface-800 border-2 rounded-xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 transition-all"
            :class="formErrors.video_url ? 'border-rose-500' : 'border-surface-200 dark:border-surface-700'"
        >
        {{-- Video Preview --}}
        <template x-if="formData.video_url && getYoutubeId(formData.video_url)">
            <div class="aspect-video rounded-xl overflow-hidden bg-black mt-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <img :src="'https://img.youtube.com/vi/' + getYoutubeId(formData.video_url) + '/maxresdefault.jpg'" class="w-full h-full object-cover">
            </div>
        </template>
        <template x-if="formErrors.video_url">
            <p class="text-sm font-medium text-rose-500" x-text="formErrors.video_url[0]"></p>
        </template>
    </div>
</div>
