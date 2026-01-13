{{-- Form Modal (Create/Edit) --}}
<template x-teleport="body">
    <div 
        x-show="showFormModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="showFormModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity"
            @click="closeFormModal()"
        ></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-end sm:items-center justify-center sm:p-4">
            <div 
                x-show="showFormModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-6xl h-[95vh] sm:h-[90vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                {{-- Header --}}
                @include('galleries.partials.form-modal.header')

                {{-- Main Layout --}}
                <div class="flex flex-1 overflow-hidden relative">
                    {{-- Sidebar Navigation --}}
                    @include('galleries.partials.form-modal.sidebar')

                    {{-- Content Scroll Area --}}
                    <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 relative scroll-smooth" id="form-scroll-container">
                        <form id="galleryForm" @submit.prevent="submitForm()" class="p-4 sm:p-8 pb-10 max-w-4xl mx-auto space-y-6 sm:space-y-8">
                            {{-- Tab 1: Basic Info --}}
                            @include('galleries.partials.form-modal.tab-basic')

                            {{-- Tab 2: Media --}}
                            @include('galleries.partials.form-modal.tab-media')

                            {{-- Tab 3: Settings --}}
                            @include('galleries.partials.form-modal.tab-settings')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
