@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div x-data="categoryApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('categories.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('categories.partials.filter')

            {{-- Table Section --}}
            @include('categories.partials.table')

            {{-- Pagination Section --}}
            @include('categories.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('categories.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('categories.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('categories.partials.form-modal')

    {{-- Detail Modal --}}
    @include('categories.partials.detail-modal')
</div>
@endsection

@push('scripts')
@include('categories.partials.scripts')
@endpush
