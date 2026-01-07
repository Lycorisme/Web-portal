@extends('layouts.app')

@section('title', 'Kelola Tag')

@section('content')
<div x-data="tagApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('tags.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('tags.partials.filter')

            {{-- Table Section --}}
            @include('tags.partials.table')

            {{-- Pagination Section --}}
            @include('tags.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('tags.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('tags.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('tags.partials.form-modal')

    {{-- Detail Modal --}}
    @include('tags.partials.detail-modal')
</div>
@endsection

@push('scripts')
@include('tags.partials.scripts')
@endpush
