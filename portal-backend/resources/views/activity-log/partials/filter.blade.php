{{-- Filter Section --}}
<div class="p-4 sm:p-6 pb-10 mb-0 border-b border-surface-200/50 dark:border-surface-800/50">
    {{-- Top Row: Per Page Selector & Search --}}
    <div class="flex items-center gap-2 sm:gap-4 mb-6">
        {{-- Per Page Selector (Left) --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-sm font-medium text-surface-600 dark:text-surface-400 hidden sm:inline">Tampilkan</span>
            <select 
                x-model="meta.per_page"
                @change="applyFilters()"
                class="px-3 py-3 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm font-medium text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all hover:bg-surface-100 dark:hover:bg-surface-700/50 cursor-pointer"
            >
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        {{-- Enhanced Search Input --}}
        <div class="flex-1 min-w-0">
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-theme-500/20 to-theme-600/20 rounded-2xl blur-xl opacity-0 group-focus-within:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center">
                    <div class="absolute left-3 sm:left-4 flex items-center justify-center">
                        <i data-lucide="search" class="w-5 h-5 text-surface-400 group-focus-within:text-theme-500 transition-colors"></i>
                    </div>
                    <input 
                        type="text"
                        x-model="filters.search"
                        @keyup.enter="applyFilters()"
                        placeholder="Cari..."
                        class="w-full pl-10 sm:pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/80 border-2 border-surface-200 dark:border-surface-700 rounded-2xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-theme-500 dark:focus:border-theme-500 transition-all duration-300 shadow-sm"
                    >
                    <div class="absolute right-3 flex items-center gap-1.5" x-show="filters.search" style="display: none;">
                        <button 
                            @click="filters.search = ''; applyFilters()"
                            class="p-1.5 hover:bg-surface-200 dark:hover:bg-surface-700 rounded-lg transition-colors"
                        >
                            <i data-lucide="x" class="w-4 h-4 text-surface-400"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Auto Delete Button --}}
        <button 
            @click="openAutoDeleteModal()"
            class="flex-shrink-0 inline-flex items-center justify-center p-3 sm:px-4 sm:py-3 bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-2xl hover:bg-surface-50 dark:hover:bg-surface-700 hover:border-orange-200 dark:hover:border-orange-500/30 text-surface-600 dark:text-surface-300 hover:text-orange-600 dark:hover:text-orange-400 transition-all duration-300 group/autodel shadow-sm"
            title="Auto Delete Settings"
        >
            <i data-lucide="trash-2" class="w-5 h-5 group-hover/autodel:scale-110 transition-transform"></i>
            <span class="hidden sm:inline ml-2 font-medium">Auto Delete</span>
        </button>
    </div>

    {{-- Filter Row --}}
    <div class="flex flex-col xl:flex-row xl:items-end gap-4">
        {{-- Date Range --}}
        <div class="grid grid-cols-2 gap-3 flex-shrink-0 w-full xl:w-auto">
            <div>
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Dari Tanggal</label>
                <input 
                    type="date"
                    x-model="filters.date_from"
                    class="w-full px-3 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Sampai Tanggal</label>
                <input 
                    type="date"
                    x-model="filters.date_to"
                    class="w-full px-3 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                >
            </div>
        </div>

        {{-- Dropdown Filters --}}
        <div class="grid grid-cols-3 gap-2 sm:gap-3 flex-1">
            {{-- User Filter --}}
            <div>
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">User</label>
                <select 
                    x-model="filters.user_id"
                    class="w-full px-3 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                >
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Action Filter --}}
            <div>
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Aksi</label>
                <select 
                    x-model="filters.action"
                    class="w-full px-3 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                >
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}">{{ $action }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Level Filter --}}
            <div>
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1.5">Level</label>
                <select 
                    x-model="filters.level"
                    class="w-full px-3 py-2.5 bg-surface-50 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 rounded-xl text-sm text-surface-900 dark:text-white focus:ring-2 focus:ring-theme-500 focus:border-transparent transition-all"
                >
                    <option value="">Semua Level</option>
                    @foreach($levels as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 flex-shrink-0 w-full xl:w-auto">
            <button 
                @click="applyFilters()"
                class="flex-1 xl:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-theme-gradient text-white font-medium text-sm rounded-xl hover:opacity-90 transition-all shadow-lg shadow-theme-500/20"
            >
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span class="hidden xl:inline">Terapkan</span>
                <span class="inline xl:hidden">Terapkan Filter</span>
            </button>

            {{-- Unhide/Trash Toggle Button --}}
            <button 
                @click="toggleTrash()"
                class="flex items-center justify-center p-2.5 rounded-xl font-medium text-sm transition-all border shadow-sm"
                :class="showTrash 
                    ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white border-rose-600 shadow-rose-500/25 hover:shadow-rose-500/40 hover:from-rose-600 hover:to-rose-700' 
                    : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-300 border-surface-200 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700 hover:border-surface-300 dark:hover:border-surface-600'"
                :title="showTrash ? 'Kembali' : 'Tong Sampah'"
            >
                <span x-show="!showTrash">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </span>
                <span x-show="showTrash" style="display: none">
                    <i data-lucide="archive-restore" class="w-4 h-4"></i>
                </span>
            </button>

            {{-- Reset Button (Icon Only) --}}
            <button 
                @click="resetFilters()"
                class="p-2.5 bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300 rounded-xl hover:bg-surface-200 dark:hover:bg-surface-700 transition-all"
                title="Reset Filter"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>
