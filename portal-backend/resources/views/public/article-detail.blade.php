@extends('public.layouts.public')

@section('meta_title', $article->title)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 160))

@section('content')
<div class="w-full max-w-full overflow-x-hidden bg-[#020617] min-h-screen">
    
    {{-- Reading Progress Bar --}}
    <div x-data="{ progress: 0 }" 
         @scroll.window="progress = Math.min(100, (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100)"
         class="fixed top-0 left-0 right-0 h-1 z-[100]">
        <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400 shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-all duration-150 ease-out"
             :style="'width: ' + progress + '%'"></div>
    </div>

    {{-- Hero Section --}}
    @include('public.article.partials.hero-section')

    {{-- Main Content --}}
    <main class="relative z-20 -mt-6 sm:-mt-10 lg:-mt-20 pb-16 sm:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                
                {{-- Left Column: Article Content --}}
                <article class="flex-1 min-w-0">
                    
                    {{-- Article Container --}}
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[32px] sm:rounded-[40px] border border-white/5 p-6 sm:p-10 lg:p-12 shadow-2xl relative overflow-hidden mb-8">
                        
                        {{-- Decorative Background Gradients --}}
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

                        {{-- Content --}}
                        <div class="relative z-10 prose prose-lg md:prose-xl prose-invert max-w-none
                                    prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-white
                                    prose-p:text-slate-300 prose-p:leading-relaxed prose-p:font-light
                                    prose-a:text-emerald-400 prose-a:font-semibold prose-a:no-underline hover:prose-a:text-emerald-300 hover:prose-a:underline transition-colors
                                    prose-blockquote:border-l-4 prose-blockquote:border-emerald-500 prose-blockquote:bg-slate-800/50 prose-blockquote:pl-6 prose-blockquote:py-4 prose-blockquote:pr-4 prose-blockquote:rounded-r-2xl prose-blockquote:not-italic prose-blockquote:text-slate-200
                                    prose-ul:list-disc prose-ul:pl-6 prose-ul:text-slate-300
                                    prose-ol:list-decimal prose-ol:pl-6 prose-ol:text-slate-300
                                    prose-li:marker:text-emerald-500
                                    prose-img:rounded-3xl prose-img:shadow-lg prose-img:border prose-img:border-white/5 prose-img:w-full
                                    prose-code:text-emerald-400 prose-code:bg-slate-950 prose-code:px-2 prose-code:py-1 prose-code:rounded-lg prose-code:border prose-code:border-white/5
                                    prose-pre:bg-slate-950 prose-pre:border prose-pre:border-white/5 prose-pre:rounded-2xl prose-pre:shadow-inner
                                    [&>*:first-child]:mt-0">
                            
                            {!! $article->content !!}
                        </div>

                        {{-- Footer: Tags --}}
                        @if($article->tags && $article->tags->count() > 0)
                            <div class="mt-12 pt-8 border-t border-white/5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-bold text-slate-500 mr-2 uppercase tracking-wider flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        Tags:
                                    </span>
                                    @foreach($article->tags as $tag)
                                        <a href="{{ route('public.articles', ['tag' => $tag->slug]) }}" wire:navigate 
                                           class="px-4 py-1.5 bg-slate-800/50 hover:bg-emerald-500/20 text-slate-400 hover:text-emerald-400 text-xs font-bold uppercase tracking-wider rounded-lg border border-white/5 hover:border-emerald-500/50 transition-all duration-300">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        {{-- Engagement Bar --}}
                        <div class="mt-8 pt-6 border-t border-white/5">
                            @include('public.article.partials.engagement-bar')
                        </div>
                    </div>
                    
                    {{-- Comments Section --}}
                    @include('public.article.partials.comments-section')

                </article>

                {{-- Right Column: Sidebar --}}
                <div class="w-full lg:w-[22rem] flex-shrink-0 space-y-8">
                     @include('public.article.partials.sidebar')
                </div>

            </div>
        </div>
    </main>
</div>
@endsection
