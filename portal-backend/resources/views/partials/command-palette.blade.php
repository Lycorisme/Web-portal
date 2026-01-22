{{-- Command Palette / Global Search --}}
<div x-data="commandPalette()"
     x-show="isOpen"
     x-on:keydown.escape.window="close()"
     x-on:keydown.ctrl.k.window.prevent="toggle()"
     x-on:keydown.meta.k.window.prevent="toggle()"
     x-on:keydown.arrow-down.prevent="navigateDown()"
     x-on:keydown.arrow-up.prevent="navigateUp()"
     x-on:keydown.enter.prevent="selectCurrent()"
     class="fixed inset-0 z-[100] overflow-y-auto"
     x-cloak>

    {{-- Backdrop --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-surface-950/60 dark:bg-black/70 backdrop-blur-sm">
    </div>

    {{-- Modal Container --}}
    <div class="flex min-h-full items-start justify-center p-4 pt-[10vh] sm:pt-[15vh]">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.outside="close()"
             class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white dark:bg-surface-900 shadow-2xl shadow-surface-900/30 dark:shadow-black/50 ring-1 ring-surface-200/50 dark:ring-surface-700/50">

            {{-- Search Header --}}
            <div class="flex items-center gap-4 px-5 py-4 border-b border-surface-200/80 dark:border-surface-700/80">
                <div class="flex-shrink-0">
                    <i data-lucide="search" class="w-5 h-5 text-theme-500"></i>
                </div>
                <input
                    x-ref="searchInput"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    type="text"
                    placeholder="Cari artikel, galeri, kategori, atau ketik perintah..."
                    class="flex-1 bg-transparent text-lg text-surface-900 dark:text-white placeholder:text-surface-400 dark:placeholder:text-surface-500 outline-none">
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 text-theme-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-medium text-surface-400 bg-surface-100 dark:bg-surface-800 rounded-lg border border-surface-200 dark:border-surface-700">
                        ESC
                    </kbd>
                </div>
            </div>

            {{-- Results Container --}}
            <div class="max-h-[60vh] overflow-y-auto overscroll-contain">

                {{-- Quick Actions (shown when no query) --}}
                <template x-if="!query">
                    <div class="p-3">
                        <div class="px-3 py-2 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider">
                            Navigasi Cepat
                        </div>
                        <div class="space-y-1">
                            <template x-for="(action, index) in quickActions" :key="action.url">
                                <a :href="action.url"
                                   @mouseenter="selectedIndex = index"
                                   :class="{ 'bg-theme-50 dark:bg-theme-900/30': selectedIndex === index }"
                                   class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors group">
                                    <div :class="selectedIndex === index ? 'bg-theme-gradient' : 'bg-surface-100 dark:bg-surface-800'"
                                         class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
                                        <i :data-lucide="action.icon"
                                           :class="selectedIndex === index ? 'text-white' : 'text-surface-500 dark:text-surface-400'"
                                           class="w-5 h-5"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-surface-900 dark:text-white" x-text="action.label"></div>
                                        <div class="text-sm text-surface-500 dark:text-surface-400" x-text="action.description"></div>
                                    </div>
                                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i data-lucide="corner-down-left" class="w-4 h-4 text-surface-400"></i>
                                    </div>
                                </a>
                            </template>
                        </div>

                        {{-- Recent Searches --}}
                        <template x-if="recentSearches.length > 0">
                            <div class="mt-4">
                                <div class="flex items-center justify-between px-3 py-2">
                                    <span class="text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider">
                                        Pencarian Terbaru
                                    </span>
                                    <button @click="clearRecentSearches()" class="text-xs text-surface-400 hover:text-theme-500 transition-colors">
                                        Hapus Semua
                                    </button>
                                </div>
                                <div class="space-y-1">
                                    <template x-for="recent in recentSearches.slice(0, 5)" :key="recent">
                                        <button @click="query = recent; search()"
                                                class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors text-left">
                                            <i data-lucide="history" class="w-4 h-4 text-surface-400"></i>
                                            <span class="text-surface-600 dark:text-surface-300" x-text="recent"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Search Results --}}
                <template x-if="query && results.length > 0">
                    <div class="p-3">
                        <template x-for="(group, groupIndex) in results" :key="group.type">
                            <div class="mb-4 last:mb-0">
                                <div class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider">
                                    <i :data-lucide="group.icon" class="w-3.5 h-3.5"></i>
                                    <span x-text="group.label"></span>
                                    <span class="text-surface-300 dark:text-surface-600" x-text="'(' + group.items.length + ')'"></span>
                                </div>
                                <div class="space-y-1">
                                    <template x-for="(item, itemIndex) in group.items" :key="item.id">
                                        <a :href="item.url"
                                           @mouseenter="setSelectedByFlatIndex(groupIndex, itemIndex)"
                                           :class="{ 'bg-theme-50 dark:bg-theme-900/30 ring-1 ring-theme-200 dark:ring-theme-800': isItemSelected(groupIndex, itemIndex) }"
                                           class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-all group">

                                            {{-- Thumbnail/Icon --}}
                                            <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-surface-100 dark:bg-surface-800">
                                                <template x-if="item.image">
                                                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!item.image && item.color">
                                                    <div class="w-full h-full flex items-center justify-center" :style="'background-color: ' + item.color">
                                                        <i :data-lucide="group.icon" class="w-5 h-5 text-white"></i>
                                                    </div>
                                                </template>
                                                <template x-if="!item.image && !item.color">
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i :data-lucide="group.icon" class="w-5 h-5 text-surface-400"></i>
                                                    </div>
                                                </template>
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-surface-900 dark:text-white truncate" x-text="item.title"></div>
                                                <div class="text-sm text-surface-500 dark:text-surface-400 truncate" x-text="item.subtitle"></div>
                                            </div>

                                            {{-- Badge --}}
                                            <template x-if="item.badge">
                                                <span :class="{
                                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': item.badge.color === 'emerald',
                                                    'bg-slate-100 text-slate-700 dark:bg-slate-900/30 dark:text-slate-400': item.badge.color === 'slate',
                                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': item.badge.color === 'amber',
                                                    'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400': item.badge.color === 'rose',
                                                    'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400': item.badge.color === 'violet',
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': item.badge.color === 'blue',
                                                }" class="px-2.5 py-1 text-xs font-medium rounded-lg flex-shrink-0" x-text="item.badge.label">
                                                </span>
                                            </template>

                                            {{-- Arrow --}}
                                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i data-lucide="arrow-right" class="w-4 h-4 text-surface-400"></i>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- No Results --}}
                <template x-if="query && results.length === 0 && !isLoading && hasSearched">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
                            <i data-lucide="search-x" class="w-8 h-8 text-surface-400"></i>
                        </div>
                        <p class="text-surface-600 dark:text-surface-400 font-medium">Tidak ada hasil untuk "<span x-text="query" class="text-theme-500"></span>"</p>
                        <p class="text-sm text-surface-400 dark:text-surface-500 mt-1">Coba kata kunci yang berbeda</p>
                    </div>
                </template>

            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between px-5 py-3 border-t border-surface-200/80 dark:border-surface-700/80 bg-surface-50/50 dark:bg-surface-800/50">
                <div class="flex items-center gap-4 text-xs text-surface-400 dark:text-surface-500">
                    <div class="flex items-center gap-1.5">
                        <kbd class="px-1.5 py-0.5 bg-surface-100 dark:bg-surface-700 rounded">↑</kbd>
                        <kbd class="px-1.5 py-0.5 bg-surface-100 dark:bg-surface-700 rounded">↓</kbd>
                        <span>navigasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <kbd class="px-1.5 py-0.5 bg-surface-100 dark:bg-surface-700 rounded">↵</kbd>
                        <span>buka</span>
                    </div>
                </div>
                <div class="text-xs text-surface-400 dark:text-surface-500">
                    <span x-show="totalResults > 0" x-text="totalResults + ' hasil ditemukan'"></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Command Palette Script --}}
<script>
function commandPalette() {
    return {
        isOpen: false,
        query: '',
        results: [],
        totalResults: 0,
        isLoading: false,
        hasSearched: false,
        selectedIndex: 0,
        recentSearches: JSON.parse(localStorage.getItem('recentSearches') || '[]'),

        quickActions: [
            { icon: 'layout-dashboard', label: 'Dashboard', description: 'Kembali ke dashboard utama', url: '{{ route("dashboard") }}' },
            { icon: 'file-text', label: 'Artikel', description: 'Kelola semua artikel', url: '{{ route("articles") }}' },
            { icon: 'image', label: 'Galeri', description: 'Kelola galeri media', url: '{{ route("galleries") }}' },
            @if(Auth::user()?->canManageCategories())
            { icon: 'folder', label: 'Kategori', description: 'Kelola kategori artikel', url: '{{ route("categories") }}' },
            { icon: 'tag', label: 'Tag', description: 'Kelola tag artikel', url: '{{ route("tags") }}' },
            @endif
            @if(Auth::user()?->canManageUsers())
            { icon: 'users', label: 'Pengguna', description: 'Kelola pengguna sistem', url: '{{ route("users") }}' },
            @endif
            @if(Auth::user()?->canAccessSettings())
            { icon: 'settings', label: 'Pengaturan', description: 'Konfigurasi sistem', url: '{{ route("settings") }}' },
            @endif
            { icon: 'user', label: 'Profil Saya', description: 'Lihat dan edit profil', url: '{{ route("profile") }}' },
            { icon: 'globe', label: 'Halaman Publik', description: 'Buka website publik', url: '{{ route("public.home") }}' },
        ],

        init() {
            // Listen for custom event to open command palette
            window.addEventListener('open-command-palette', () => this.open());
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        open() {
            this.isOpen = true;
            this.query = '';
            this.results = [];
            this.selectedIndex = 0;
            this.hasSearched = false;
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
                lucide.createIcons();
            });
        },

        close() {
            this.isOpen = false;
            this.query = '';
            this.results = [];
        },

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.totalResults = 0;
                this.hasSearched = false;
                return;
            }

            this.isLoading = true;
            this.hasSearched = false;

            try {
                const response = await fetch(`{{ route('global-search') }}?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();

                this.results = data.results;
                this.totalResults = data.total;
                this.selectedIndex = 0;
                this.hasSearched = true;

                // Save to recent searches
                if (this.query.length >= 2 && data.total > 0) {
                    this.addToRecentSearches(this.query);
                }

                this.$nextTick(() => lucide.createIcons());
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
                this.totalResults = 0;
            } finally {
                this.isLoading = false;
            }
        },

        navigateDown() {
            const maxIndex = this.query ? this.getFlatItemsCount() - 1 : this.quickActions.length - 1;
            this.selectedIndex = Math.min(this.selectedIndex + 1, maxIndex);
        },

        navigateUp() {
            this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
        },

        selectCurrent() {
            if (!this.query) {
                // Navigate to quick action
                if (this.quickActions[this.selectedIndex]) {
                    window.location.href = this.quickActions[this.selectedIndex].url;
                }
            } else {
                // Navigate to search result
                const item = this.getFlatItemByIndex(this.selectedIndex);
                if (item) {
                    window.location.href = item.url;
                }
            }
        },

        getFlatItemsCount() {
            return this.results.reduce((count, group) => count + group.items.length, 0);
        },

        getFlatItemByIndex(flatIndex) {
            let currentIndex = 0;
            for (const group of this.results) {
                for (const item of group.items) {
                    if (currentIndex === flatIndex) {
                        return item;
                    }
                    currentIndex++;
                }
            }
            return null;
        },

        setSelectedByFlatIndex(groupIndex, itemIndex) {
            let flatIndex = 0;
            for (let i = 0; i < groupIndex; i++) {
                flatIndex += this.results[i].items.length;
            }
            flatIndex += itemIndex;
            this.selectedIndex = flatIndex;
        },

        isItemSelected(groupIndex, itemIndex) {
            let flatIndex = 0;
            for (let i = 0; i < groupIndex; i++) {
                flatIndex += this.results[i].items.length;
            }
            flatIndex += itemIndex;
            return this.selectedIndex === flatIndex;
        },

        addToRecentSearches(query) {
            // Remove if already exists
            this.recentSearches = this.recentSearches.filter(s => s.toLowerCase() !== query.toLowerCase());
            // Add to beginning
            this.recentSearches.unshift(query);
            // Keep only last 10
            this.recentSearches = this.recentSearches.slice(0, 10);
            // Save to localStorage
            localStorage.setItem('recentSearches', JSON.stringify(this.recentSearches));
        },

        clearRecentSearches() {
            this.recentSearches = [];
            localStorage.removeItem('recentSearches');
        }
    }
}
</script>
