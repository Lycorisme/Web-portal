{{-- Filter Section --}}
<div class="p-4 sm:p-6 pb-6 border-b border-surface-200/50 dark:border-surface-800/50">
    {{-- Top Row: Per Page Selector & Search & Empty Trash --}}
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
                <div class="absolute inset-0 bg-gradient-to-r from-rose-500/20 to-rose-600/20 rounded-2xl blur-xl opacity-0 group-focus-within:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center">
                    <div class="absolute left-3 sm:left-4 flex items-center justify-center">
                        <i data-lucide="search" class="w-5 h-5 text-surface-400 group-focus-within:text-rose-500 transition-colors"></i>
                    </div>
                    <input 
                        type="text"
                        x-model="filters.search"
                        @keyup.enter="applyFilters()"
                        placeholder="Cari item..."
                        class="w-full pl-10 sm:pl-12 pr-4 py-3 bg-surface-50 dark:bg-surface-800/80 border-2 border-surface-200 dark:border-surface-700 rounded-2xl text-sm text-surface-900 dark:text-white placeholder-surface-400 focus:ring-0 focus:border-rose-500 dark:focus:border-rose-500 transition-all duration-300 shadow-sm"
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

        {{-- Empty Trash Button --}}
        <button 
            @click="emptyTrash()"
            :disabled="counts.all === 0"
            class="flex-shrink-0 inline-flex items-center justify-center p-3 sm:px-4 sm:py-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-2xl hover:from-rose-600 hover:to-rose-700 transition-all duration-300 group/empty shadow-lg shadow-rose-500/25 disabled:opacity-50 disabled:cursor-not-allowed"
            title="Kosongkan Tong Sampah"
        >
            <i data-lucide="trash-2" class="w-5 h-5 group-hover/empty:scale-110 transition-transform"></i>
            <span class="hidden sm:inline ml-2 font-medium">Kosongkan</span>
        </button>
    </div>

    {{-- Type Filter Cards --}}
    <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6 pb-2 scrollbar-thin scrollbar-thumb-surface-300 dark:scrollbar-thumb-surface-600">
        <div class="flex gap-2 sm:gap-3 min-w-max">
            {{-- All Types Card --}}
            <button 
                @click="filters.type = 'all'; applyFilters()"
                class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl font-medium text-sm transition-all duration-300 border whitespace-nowrap"
                :class="filters.type === 'all' 
                    ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white border-rose-600 shadow-lg shadow-rose-500/25' 
                    : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-300 border-surface-200 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700 hover:border-surface-300 dark:hover:border-surface-600'"
            >
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                    :class="filters.type === 'all' ? 'bg-white/20' : 'bg-surface-100 dark:bg-surface-700'">
                    <i data-lucide="layers" class="w-4 h-4" :class="filters.type === 'all' ? 'text-white' : 'text-surface-500 dark:text-surface-400'"></i>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-xs opacity-70">Semua</span>
                    <span class="font-bold" x-text="counts.all || 0"></span>
                </div>
            </button>

            @foreach($typeLabels as $type => $label)
            <button 
                @click="filters.type = '{{ $type }}'; applyFilters()"
                class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl font-medium text-sm transition-all duration-300 border whitespace-nowrap"
                :class="filters.type === '{{ $type }}' 
                    ? 'bg-gradient-to-r from-rose-500 to-rose-600 text-white border-rose-600 shadow-lg shadow-rose-500/25' 
                    : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-300 border-surface-200 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700 hover:border-surface-300 dark:hover:border-surface-600'"
            >
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                    :class="filters.type === '{{ $type }}' ? 'bg-white/20' : 'bg-surface-100 dark:bg-surface-700'">
                    <i data-lucide="{{ $typeIcons[$type] }}" class="w-4 h-4" :class="filters.type === '{{ $type }}' ? 'text-white' : 'text-surface-500 dark:text-surface-400'"></i>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-xs opacity-70">{{ $label }}</span>
                    <span class="font-bold" x-text="counts['{{ $type }}'] || 0"></span>
                </div>
            </button>
            @endforeach
        </div>
    </div>
</div>
