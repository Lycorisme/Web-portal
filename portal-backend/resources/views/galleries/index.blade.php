@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

@section('content')
<div x-data="galleryApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('galleries.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('galleries.partials.filter')

            {{-- Grid Section --}}
            @include('galleries.partials.grid')

            {{-- Pagination Section --}}
            @include('galleries.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('galleries.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('galleries.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('galleries.partials.form-modal')

    {{-- Detail Modal --}}
    @include('galleries.partials.detail-modal')

    {{-- Image Preview Modal --}}
    @include('galleries.partials.preview-modal')
</div>
@endsection

@push('scripts')
@include('galleries.partials.scripts')
@endpush
