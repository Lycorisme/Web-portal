<div class="bg-white dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800 p-5 shadow-sm flex flex-col">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h3 class="font-bold text-surface-900 dark:text-white">Distribusi</h3>
            <p class="text-xs text-surface-500 dark:text-surface-400">Kategori Berita</p>
        </div>
        <a href="#" class="text-xs text-primary-600 hover:underline">Detail</a>
    </div>
    
    <div class="flex-1 flex items-center gap-6">
        {{-- Circular Chart --}}
        <div class="relative w-32 h-32 flex-shrink-0">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" class="stroke-surface-100 dark:stroke-surface-800" stroke-width="12"></circle>
                @php $catOffset = 0; @endphp
                @foreach($categoryData as $index => $category)
                    @if($category['count'] > 0)
                        @php
                            $catCircum = 2 * M_PI * 40;
                            $catPct = $totalCategoryArticles > 0 ? ($category['count'] / $totalCategoryArticles) * 100 : 0;
                            $catDash = ($catCircum * $catPct) / 100;
                            $catGap = $catCircum - $catDash;
                            $catDashOffset = -($catCircum * $catOffset) / 100;
                            $catOffset += $catPct;
                            // Colors matching theme
                            $fillColors = ['#6366f1', '#06b6d4', '#f59e0b', '#ec4899', '#10b981']; 
                            $strokeColor = $fillColors[$index % 5];
                        @endphp
                        <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $strokeColor }}"
                            stroke-width="12" 
                            stroke-dasharray="{{ $catDash }} {{ $catGap }}" 
                            stroke-dashoffset="{{ $catDashOffset }}"
                            stroke-linecap="butt" 
                            class="transition-all duration-500 hover:opacity-80"></circle>
                    @endif
                @endforeach
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-bold text-surface-900 dark:text-white">{{ $stats['total_articles'] }}</span>
            </div>
        </div>
        
        {{-- Legend --}}
        <div class="flex-1 grid grid-cols-1 gap-1.5 overflow-hidden">
            @forelse($categoryData->take(4) as $index => $category)
                @php $fillColors = ['bg-primary-500', 'bg-accent-cyan', 'bg-accent-amber', 'bg-pink-500', 'bg-emerald-500']; @endphp
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 truncate">
                        <span class="w-2 h-2 rounded-full {{ $fillColors[$index % 5] }}"></span>
                        <span class="text-surface-600 dark:text-surface-400 truncate max-w-[80px]">{{ $category['name'] }}</span>
                    </div>
                    <span class="font-semibold text-surface-900 dark:text-white">{{ $category['count'] }}</span>
                </div>
            @empty
                <p class="text-xs text-surface-400 italic">Belum ada data</p>
            @endforelse
            @if($categoryData->count() > 4)
                <p class="text-[10px] text-surface-400 mt-1">+{{ $categoryData->count() - 4 }} lainnya</p>
            @endif
        </div>
    </div>
</div>
