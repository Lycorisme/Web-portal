<section class="mb-20">
    <div class="flex items-center justify-between mb-10">
        <h2 class="text-2xl md:text-4xl font-black text-slate-900">Berita Terkini</h2>
        <div class="hidden md:flex gap-2">
            <button class="p-2 border border-slate-200 rounded-full hover:bg-white transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button class="p-2 bg-white border border-slate-200 rounded-full hover:bg-slate-50 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
        @forelse($latestArticles as $article)
            @php
                $categoryColors = [
                    'indigo' => 'text-indigo-600',
                    'rose' => 'text-rose-500',
                    'emerald' => 'text-emerald-600',
                    'amber' => 'text-amber-600',
                    'sky' => 'text-sky-600',
                ];
                $colorKeys = array_keys($categoryColors);
                $colorKey = $colorKeys[$loop->index % count($colorKeys)];
                $colorClass = $categoryColors[$colorKey];
            @endphp
            <article class="group cursor-pointer">
                <a href="{{ route('public.article.show', $article->slug) }}" class="block">
                    <div class="relative h-64 rounded-3xl overflow-hidden mb-5">
                        <img src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : 'https://images.unsplash.com/photo-1493612276216-ee3925520721?auto=format&fit=crop&q=80&w=500' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            alt="{{ $article->title }}">
                        @if($article->categoryRelation)
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $colorClass }}">
                                {{ $article->categoryRelation->name }}
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold mb-3 group-hover:text-indigo-600 transition">{{ Str::limit($article->title, 70) }}</h3>
                    <p class="text-slate-500 text-sm line-clamp-2 mb-4">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}</p>
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-widest">
                        {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }} • {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} Menit Baca
                    </span>
                </a>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-slate-400 text-lg">Belum ada artikel terbaru</p>
            </div>
        @endforelse
    </div>

    @if($latestArticles->count() > 0)
        <div class="mt-16 text-center">
            <a href="{{ route('public.articles') }}" class="inline-block border-2 border-slate-200 px-10 py-4 rounded-2xl font-bold text-slate-800 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300">
                Lihat Berita Lainnya
            </a>
        </div>
    @endif
</section>
