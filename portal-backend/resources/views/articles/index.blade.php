@extends('layouts.app')

@section('title', 'Kelola Berita')

@section('content')
<div x-data="articleApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('articles.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('articles.partials.filter')

            {{-- Table Section --}}
            @include('articles.partials.table')

            {{-- Pagination Section --}}
            @include('articles.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('articles.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('articles.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('articles.partials.form-modal')

    {{-- Detail Modal --}}
    @include('articles.partials.detail-modal')
</div>
@endsection

@push('scripts')
@include('articles.partials.scripts')
@endpush
