{{-- Header Component --}}
<header class="sticky top-0 z-30 bg-white/70 dark:bg-surface-900/70 backdrop-blur-xl border-b border-surface-200/50 dark:border-surface-800/50">
    <div class="flex items-center justify-between px-4 lg:px-8 py-4">
        <div class="flex items-center gap-4">
            {{-- Sidebar Toggle Button --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-2.5 rounded-xl bg-surface-100 dark:bg-surface-800 hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200 group">
                <i data-lucide="panel-left-close" x-show="sidebarOpen" x-cloak
                    class="w-5 h-5 text-surface-600 dark:text-surface-400 group-hover:text-theme-600 transition-colors"></i>
                <i data-lucide="panel-left-open" x-show="!sidebarOpen"
                    class="w-5 h-5 text-surface-600 dark:text-surface-400 group-hover:text-theme-600 transition-colors"></i>
            </button>

            {{-- Search Box --}}
            <div class="hidden sm:flex items-center gap-3 px-4 py-2.5 bg-surface-100 dark:bg-surface-800/50 rounded-xl border border-transparent focus-within:border-theme-500/50 focus-within:ring-2 focus-within:ring-theme-500/20 transition-all duration-200 w-64 lg:w-80">
                <i data-lucide="search" class="w-4 h-4 text-surface-400"></i>
                <input type="text" placeholder="Cari sesuatu..."
                    class="flex-1 bg-transparent outline-none text-sm text-surface-700 dark:text-surface-300 placeholder:text-surface-400">
                <kbd class="hidden lg:inline-flex items-center px-2 py-0.5 text-xs font-medium text-surface-400 bg-surface-200 dark:bg-surface-700 rounded">⌘K</kbd>
            </div>
        </div>

        <div class="flex items-center gap-2 lg:gap-4">
            {{-- Dark Mode Toggle --}}
            <button @click="toggleDarkMode()"
                class="p-2.5 rounded-xl bg-surface-100 dark:bg-surface-800 hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200 group">
                <i data-lucide="sun" x-show="darkMode" x-cloak
                    class="w-5 h-5 text-accent-amber group-hover:rotate-45 transition-transform duration-300"></i>
                <i data-lucide="moon" x-show="!darkMode"
                    class="w-5 h-5 text-theme-600 group-hover:-rotate-12 transition-transform duration-300"></i>
            </button>

            {{-- Notifications --}}
            <div class="relative" x-data="{ showNotificationModal: false }">
                <button @click="showNotificationModal = true"
                    class="relative p-2.5 rounded-xl bg-surface-100 dark:bg-surface-800 hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
                    <i data-lucide="bell" class="w-5 h-5 text-surface-600 dark:text-surface-400"></i>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-accent-rose text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">3</span>
                </button>

                @include('partials.notification-modal')
            </div>

            {{-- User Profile Dropdown --}}
            @php $currentUser = Auth::user(); @endphp
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-3 p-1.5 pr-4 rounded-xl bg-surface-100 dark:bg-surface-800 hover:bg-surface-200 dark:hover:bg-surface-700 transition-all duration-200">
                    <div class="w-9 h-9 rounded-lg overflow-hidden flex items-center justify-center shadow-theme">
                        @if($currentUser?->avatar)
                            <img id="headerProfilePhoto" 
                                 src="{{ $currentUser->avatar }}" 
                                 alt="{{ $currentUser->name }}"
                                 class="w-full h-full object-cover rounded-lg">
                        @else
                            <div id="headerProfileInitials" class="w-full h-full bg-theme-gradient flex items-center justify-center rounded-lg">
                                <span class="text-white font-semibold text-sm">{{ strtoupper(substr($currentUser?->name ?? 'AD', 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-surface-900 dark:text-white">{{ $currentUser?->name ?? 'Admin' }}</p>
                        <p class="text-xs text-surface-500">{{ $currentUser?->role ?? 'Administrator' }}</p>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-surface-400 hidden sm:block"></i>
                </button>

                {{-- Profile Dropdown Panel --}}
                <div x-show="open" @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-surface-900 rounded-2xl shadow-2xl shadow-surface-900/20 border border-surface-200 dark:border-surface-800 overflow-hidden"
                    x-cloak>
                    <div class="p-4 border-b border-surface-200 dark:border-surface-800">
                        <p class="font-semibold text-surface-900 dark:text-white">{{ $currentUser?->name ?? 'Admin' }}</p>
                        <p class="text-sm text-surface-500">{{ $currentUser?->email ?? 'admin@portal.id' }}</p>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('profile') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                            <i data-lucide="user" class="w-4 h-4 text-surface-500"></i>
                            <span class="text-sm text-surface-700 dark:text-surface-300">Profil Saya</span>
                        </a>
                        <a href="{{ route('settings') }}" wire:navigate
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                            <i data-lucide="settings" class="w-4 h-4 text-surface-500"></i>
                            <span class="text-sm text-surface-700 dark:text-surface-300">Pengaturan</span>
                        </a>
                        <a href="{{ route('public.home') }}" target="_blank"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                            <i data-lucide="globe" class="w-4 h-4 text-surface-500"></i>
                            <span class="text-sm text-surface-700 dark:text-surface-300">Halaman Publik</span>
                        </a>
                    </div>
                    <div class="p-2 border-t border-surface-200 dark:border-surface-800">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl hover:bg-accent-rose/10 transition-colors group">
                                <i data-lucide="log-out" class="w-4 h-4 text-accent-rose"></i>
                                <span class="text-sm text-accent-rose font-medium">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

