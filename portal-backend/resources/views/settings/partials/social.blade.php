{{-- Social Media Settings Tab --}}
<div x-show="activeTab === 'social'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-violet to-pink-500 flex items-center justify-center shadow-lg shadow-accent-violet/30 flex-shrink-0">
                <i data-lucide="share-2" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Media Sosial</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Hubungkan akun media sosial Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            {{-- Facebook --}}
            <div class="space-y-2">
                <label for="facebook_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Facebook
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="facebook" class="w-4 h-4 text-white"></i>
                    </div>
                    <input type="url" name="facebook_url" id="facebook_url"
                        value="{{ $rawSettings['facebook_url'] ?? '' }}"
                        class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="https://facebook.com/yourpage">
                </div>
            </div>

            {{-- Twitter --}}
            <div class="space-y-2">
                <label for="twitter_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Twitter / X
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-surface-900 dark:bg-surface-700 rounded-lg flex items-center justify-center">
                        <i data-lucide="twitter" class="w-4 h-4 text-white"></i>
                    </div>
                    <input type="url" name="twitter_url" id="twitter_url"
                        value="{{ $rawSettings['twitter_url'] ?? '' }}"
                        class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-surface-500 focus:border-transparent transition-all duration-200"
                        placeholder="https://twitter.com/yourhandle">
                </div>
            </div>

            {{-- Instagram --}}
            <div class="space-y-2">
                <label for="instagram_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    Instagram
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 rounded-lg flex items-center justify-center">
                        <i data-lucide="instagram" class="w-4 h-4 text-white"></i>
                    </div>
                    <input type="url" name="instagram_url" id="instagram_url"
                        value="{{ $rawSettings['instagram_url'] ?? '' }}"
                        class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all duration-200"
                        placeholder="https://instagram.com/yourprofile">
                </div>
            </div>

            {{-- YouTube --}}
            <div class="space-y-2">
                <label for="youtube_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    YouTube
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="youtube" class="w-4 h-4 text-white"></i>
                    </div>
                    <input type="url" name="youtube_url" id="youtube_url"
                        value="{{ $rawSettings['youtube_url'] ?? '' }}"
                        class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                        placeholder="https://youtube.com/c/yourchannel">
                </div>
            </div>

            {{-- LinkedIn --}}
            <div class="space-y-2 md:col-span-2">
                <label for="linkedin_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300">
                    LinkedIn
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                        <i data-lucide="linkedin" class="w-4 h-4 text-white"></i>
                    </div>
                    <input type="url" name="linkedin_url" id="linkedin_url"
                        value="{{ $rawSettings['linkedin_url'] ?? '' }}"
                        class="w-full pl-16 pr-4 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-surface-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all duration-200"
                        placeholder="https://linkedin.com/company/yourcompany">
                </div>
            </div>
        </div>
    </div>
</div>
