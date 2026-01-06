@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div x-data="activityLogApp()" x-init="init()">
    {{-- Page Header --}}
    @include('activity-log.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('activity-log.partials.filter')

            {{-- Table Section --}}
            @include('activity-log.partials.table')

            {{-- Pagination Section --}}
            @include('activity-log.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('activity-log.partials.bulk-action-bar')

    {{-- Detail Modal --}}
    {{-- Auto-close menu on scroll --}}
    @include('activity-log.partials.action-menu')

    @include('activity-log.partials.detail-modal')
    @include('activity-log.partials.auto-delete-modal')
</div>
@endsection

@push('scripts')
@include('activity-log.partials.scripts')
@endpush
