{{-- Appearance Settings Tab --}}
<div x-show="activeTab === 'appearance'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
    <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-accent-amber to-accent-rose flex items-center justify-center shadow-lg shadow-accent-amber/30 flex-shrink-0">
                <i data-lucide="palette" class="w-6 sm:w-7 h-6 sm:h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-surface-900 dark:text-white">Theme Preset</h2>
                <p class="text-xs sm:text-sm text-surface-500 dark:text-surface-400">Pilih tema yang mempengaruhi tampilan button, select, hover, dan warna lainnya</p>
            </div>
        </div>

        {{-- Theme Preset Grid --}}
        <div x-data="{ selectedTheme: '{{ $rawSettings['current_theme'] ?? 'indigo' }}' }" class="space-y-4 sm:space-y-6">
            <input type="hidden" name="current_theme" x-model="selectedTheme">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Indigo Theme --}}
                <label @click="selectedTheme = 'indigo'" 
                    :class="selectedTheme === 'indigo' ? 'ring-2 ring-primary-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-4 sm:p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="indigo" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5 flex-shrink-0">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-indigo-400"></span>
                                <span class="w-3 h-3 rounded-full bg-indigo-300"></span>
                                <span class="w-3 h-3 rounded-full bg-violet-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Indigo</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Profesional & Modern</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-indigo-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-indigo-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'indigo'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Emerald Theme --}}
                <label @click="selectedTheme = 'emerald'" 
                    :class="selectedTheme === 'emerald' ? 'ring-2 ring-accent-emerald ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="emerald" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                <span class="w-3 h-3 rounded-full bg-teal-400"></span>
                                <span class="w-3 h-3 rounded-full bg-cyan-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Emerald</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Fresh & Natural</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-emerald-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-teal-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'emerald'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Rose Theme --}}
                <label @click="selectedTheme = 'rose'" 
                    :class="selectedTheme === 'rose' ? 'ring-2 ring-rose-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="rose" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 shadow-lg shadow-rose-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                <span class="w-3 h-3 rounded-full bg-pink-400"></span>
                                <span class="w-3 h-3 rounded-full bg-fuchsia-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Rose</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Elegant & Bold</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-rose-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-pink-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'rose'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Amber Theme --}}
                <label @click="selectedTheme = 'amber'" 
                    :class="selectedTheme === 'amber' ? 'ring-2 ring-amber-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="amber" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Amber</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Warm & Energetic</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-amber-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-orange-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'amber'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Cyan Theme --}}
                <label @click="selectedTheme = 'cyan'" 
                    :class="selectedTheme === 'cyan' ? 'ring-2 ring-cyan-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="cyan" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 shadow-lg shadow-cyan-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                                <span class="w-3 h-3 rounded-full bg-sky-400"></span>
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Cyan</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Cool & Calm</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-cyan-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-sky-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'cyan'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Violet Theme --}}
                <label @click="selectedTheme = 'violet'" 
                    :class="selectedTheme === 'violet' ? 'ring-2 ring-violet-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="violet" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-violet-400"></span>
                                <span class="w-3 h-3 rounded-full bg-purple-400"></span>
                                <span class="w-3 h-3 rounded-full bg-fuchsia-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Violet</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Creative & Luxurious</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-violet-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-purple-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'violet'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Slate Theme --}}
                <label @click="selectedTheme = 'slate'" 
                    :class="selectedTheme === 'slate' ? 'ring-2 ring-slate-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="slate" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 shadow-lg shadow-slate-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                                <span class="w-3 h-3 rounded-full bg-slate-500"></span>
                                <span class="w-3 h-3 rounded-full bg-gray-600"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Slate</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Minimal & Clean</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-slate-600 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-slate-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'slate'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Ocean Theme --}}
                <label @click="selectedTheme = 'ocean'" 
                    :class="selectedTheme === 'ocean' ? 'ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="ocean" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 via-cyan-500 to-teal-500 shadow-lg shadow-blue-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                                <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                                <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Ocean</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Deep & Serene</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-teal-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'ocean'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>

                {{-- Sunset Theme --}}
                <label @click="selectedTheme = 'sunset'" 
                    :class="selectedTheme === 'sunset' ? 'ring-2 ring-orange-500 ring-offset-2 dark:ring-offset-surface-900' : ''"
                    class="relative cursor-pointer group rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 hover:shadow-lg transition-all duration-300">
                    <input type="radio" name="theme_preset" value="sunset" class="sr-only" x-model="selectedTheme">
                    <div class="flex items-start gap-4">
                        <div class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 via-rose-500 to-pink-600 shadow-lg shadow-orange-500/30"></div>
                            <div class="flex gap-1">
                                <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-surface-900 dark:text-white mb-1">Sunset</h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400">Vibrant & Dynamic</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 w-full bg-gradient-to-r from-orange-500 to-rose-500 rounded-full"></div>
                                <div class="h-2 w-3/4 bg-pink-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div x-show="selectedTheme === 'sunset'" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-accent-emerald rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Theme Info --}}
            <div class="mt-6 p-4 bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-200 dark:border-surface-700">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="info" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-surface-900 dark:text-white mb-1">Tentang Theme Preset</h4>
                        <p class="text-sm text-surface-500 dark:text-surface-400">
                            Theme yang dipilih akan mempengaruhi warna button, select, hover state, link, badge, dan elemen UI lainnya di seluruh portal. 
                            Perubahan akan diterapkan setelah Anda menyimpan pengaturan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
