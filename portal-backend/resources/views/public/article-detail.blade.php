@extends('public.layouts.public')

@section('meta_title', $article->title)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 160))

@section('content')
<div class="w-full max-w-full overflow-x-hidden">
    
    {{-- Reading Progress Bar --}}
    <div x-data="{ progress: 0 }" 
         @scroll.window="progress = Math.min(100, (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100)"
         class="fixed top-0 left-0 right-0 h-1 z-[100]">
        <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400 transition-all duration-150 ease-out"
             :style="'width: ' + progress + '%'"></div>
    </div>

    {{-- Hero Section --}}
    @include('public.article.partials.hero-section')

    {{-- Main Content --}}
    <main class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10 lg:py-14">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
            
            {{-- Article Content --}}
            <article class="flex-1 min-w-0 w-full lg:w-auto">
                
                {{-- Article Body --}}
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl lg:rounded-3xl border border-slate-800/50 p-4 sm:p-6 lg:p-8">
                    <div class="prose prose-sm sm:prose-base lg:prose-lg prose-invert max-w-none w-full
                                prose-headings:text-white prose-headings:font-bold prose-headings:break-words
                                prose-p:text-slate-300 prose-p:leading-relaxed prose-p:break-words
                                prose-a:text-emerald-400 prose-a:no-underline hover:prose-a:underline prose-a:break-all
                                prose-strong:text-white
                                prose-blockquote:border-l-4 prose-blockquote:border-emerald-500 prose-blockquote:pl-4 prose-blockquote:sm:pl-6 prose-blockquote:italic prose-blockquote:text-slate-200 prose-blockquote:bg-emerald-500/5 prose-blockquote:py-3 prose-blockquote:rounded-r-xl
                                prose-ul:space-y-1 prose-li:text-slate-300
                                prose-img:rounded-xl prose-img:lg:rounded-2xl prose-img:border prose-img:border-slate-700 prose-img:max-w-full prose-img:h-auto
                                prose-pre:bg-slate-950 prose-pre:border prose-pre:border-slate-800 prose-pre:rounded-xl prose-pre:overflow-x-auto prose-pre:max-w-full prose-pre:text-xs prose-pre:sm:text-sm
                                prose-code:text-emerald-400 prose-code:bg-slate-800 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:text-xs prose-code:sm:text-sm prose-code:before:content-none prose-code:after:content-none prose-code:break-all
                                prose-table:text-sm prose-table:block prose-table:overflow-x-auto prose-table:max-w-full
                                [&_*]:max-w-full [&_img]:max-w-full [&_iframe]:max-w-full [&_video]:max-w-full [&_table]:max-w-full [&_pre]:max-w-full">
                        {!! $article->content !!}
                    </div>
                </div>

                {{-- Tags --}}
                @if($article->tags && $article->tags->count() > 0)
                    <div class="mt-4 sm:mt-6 p-4 sm:p-5 bg-slate-900/30 rounded-xl sm:rounded-2xl border border-slate-800/30">
                        <p class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 sm:mb-4">Tags</p>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" wire:navigate 
                                   class="px-2.5 sm:px-3 py-1 sm:py-1.5 bg-slate-800/50 hover:bg-emerald-500 text-slate-400 hover:text-white text-[10px] sm:text-xs font-semibold rounded-lg border border-slate-700/50 hover:border-emerald-500 transition-all">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Engagement Bar --}}
                @include('public.article.partials.engagement-bar')

                {{-- Comments Section --}}
                @include('public.article.partials.comments-section')
            </article>

            {{-- Sidebar --}}
            @include('public.article.partials.sidebar')
        </div>
    </main>
</div>
@endsection
