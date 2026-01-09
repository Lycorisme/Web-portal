@extends('layouts.app')

@section('title', 'IP Terblokir')

@section('content')
<div x-data="blockedClientApp()" x-init="init()">
    {{-- Enhanced Page Header --}}
    @include('blocked-clients.partials.header')

    {{-- Main Content --}}
    <div class="animate-slide-up" style="animation-delay: 0.1s;">
        <div class="bg-white dark:bg-surface-900/50 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-surface-200/50 dark:border-surface-800/50 overflow-hidden">
            
            {{-- Filter Section --}}
            @include('blocked-clients.partials.filter')

            {{-- Table Section --}}
            @include('blocked-clients.partials.table')

            {{-- Pagination Section --}}
            @include('blocked-clients.partials.pagination')
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @include('blocked-clients.partials.bulk-action-bar')

    {{-- Action Menu --}}
    @include('blocked-clients.partials.action-menu')

    {{-- Form Modal (Create/Edit) --}}
    @include('blocked-clients.partials.form-modal')

    {{-- Detail Modal --}}
    @include('blocked-clients.partials.detail-modal')
</div>
@endsection

@push('scripts')
@include('blocked-clients.partials.scripts')
@endpush
