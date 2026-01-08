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
                class="relative transform overflow-hidden bg-white dark:bg-surface-900 text-left shadow-2xl transition-all w-full sm:max-w-2xl h-[90vh] sm:h-auto sm:max-h-[85vh] flex flex-col sm:rounded-3xl border-t sm:border border-white/20 ring-1 ring-black/5 dark:ring-white/10"
                @click.stop
            >
                {{-- Header --}}
                @include('galleries.partials.form-modal.header')

                {{-- Form Content --}}
                <div class="flex-1 overflow-y-auto bg-white dark:bg-surface-900 scroll-smooth">
                    <form id="galleryForm" @submit.prevent="submitForm()" class="p-4 sm:p-6 space-y-6">
                        {{-- Media Type Selector --}}
                        @include('galleries.partials.form-modal.media-type-selector')

                        {{-- Title & Description --}}
                        @include('galleries.partials.form-modal.title-description')

                        {{-- Image Upload --}}
                        @include('galleries.partials.form-modal.image-upload')

                        {{-- Video URL --}}
                        @include('galleries.partials.form-modal.video-url')

                        {{-- Album & Location --}}
                        @include('galleries.partials.form-modal.album-location')

                        {{-- Event Date --}}
                        @include('galleries.partials.form-modal.event-date')

                        {{-- Settings Toggles --}}
                        @include('galleries.partials.form-modal.settings-toggles')
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
