{{-- Form Modal (Create/Edit) --}}

{{-- Trix Editor Custom Styles --}}
<style>
    trix-editor { -webkit-text-fill-color: inherit; }
    trix-editor [data-trix-mutable] { text-decoration: none !important; }
    trix-editor pre { background-color: rgba(0, 0, 0, 0.05); border-radius: 0.5rem; padding: 1rem; font-family: ui-monospace, monospace; font-size: 0.875rem; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
    .dark trix-editor pre { background-color: rgba(255, 255, 255, 0.05); }
    trix-editor code { background-color: rgba(0, 0, 0, 0.05); padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-family: ui-monospace, monospace; font-size: 0.875em; }
    .dark trix-editor code { background-color: rgba(255, 255, 255, 0.1); }
    trix-editor .attachment__caption { display: none; }
</style>

<template x-teleport="body">
    <div x-show="showFormModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div x-show="showFormModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity" @click="closeFormModal()"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-end sm:items-center justify-center sm:p-4">
            <div x-show="showFormModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-6xl h-[95vh] sm:h-[90vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10" @click.stop>
                
                {{-- Header --}}
                @include('articles.partials.form-modal.header')

                {{-- Main Layout --}}
                <div class="flex flex-1 overflow-hidden relative">
                    {{-- Sidebar Navigation --}}
                    @include('articles.partials.form-modal.sidebar')

                    {{-- Content Scroll Area --}}
                    <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 relative scroll-smooth" id="form-scroll-container">
                        <form id="articleForm" @submit.prevent="submitForm()" class="p-4 sm:p-8 pb-10 max-w-4xl mx-auto space-y-6 sm:space-y-8">
                            
                            {{-- Tab 1: Content --}}
                            @include('articles.partials.form-modal.tab-content')

                            {{-- Tab 2: Media --}}
                            @include('articles.partials.form-modal.tab-media')

                            {{-- Tab 3: SEO --}}
                            @include('articles.partials.form-modal.tab-seo')

                            {{-- Tab 4: Settings --}}
                            @include('articles.partials.form-modal.tab-settings')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

{{-- Auto-Sanitization Preview Modal --}}
@include('articles.partials.form-modal.sanitize-preview')
